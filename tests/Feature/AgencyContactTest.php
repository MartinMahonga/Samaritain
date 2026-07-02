<?php

use App\Models\AgencyContact;
use App\Models\Parcelle;
use App\Models\Property;
use App\Models\User;
use App\Notifications\AgencyContactNotification;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    Permission::create(['name' => 'manage-properties', 'guard_name' => 'web']);

    $this->admin = User::factory()->create(['is_staff' => true, 'is_active' => true]);
    $this->admin->givePermissionTo('manage-properties');

    $this->property = Property::factory()->create([
        'is_active' => true,
        'is_verify' => true,
    ]);

    $this->parcelle = Parcelle::factory()->create();
});

// ─── Property contact ──────────────────────────────────────────────────

test('la page de contact pour une propriété est accessible', function () {
    get(route('property.contact.create', $this->property))
        ->assertOk()
        ->assertSee('Contacter l\'agence')
        ->assertSee($this->property->title);
});

test('un visiteur peut soumettre un message pour une propriété', function () {
    Notification::fake();

    post(route('property.contact.store', $this->property), [
        'name' => 'Jean Dupont',
        'email' => 'jean@example.com',
        'phone' => '+242061234567',
        'subject' => 'Question à propos de '.$this->property->title,
        'message' => 'Bonjour, je suis intéressé par ce bien.',
    ])->assertRedirect(route('property.show', $this->property))
        ->assertSessionHas('success');

    expect(AgencyContact::where('contactable_id', $this->property->id)
        ->where('contactable_type', Property::class)
        ->exists()
    )->toBeTrue();

    Notification::assertSentTo($this->admin, AgencyContactNotification::class);
});

test('la soumission pour une propriété échoue avec des données invalides', function () {
    post(route('property.contact.store', $this->property), [
        'name' => '',
        'email' => 'pas-un-email',
        'message' => '',
    ])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
});

// ─── Parcelle contact ──────────────────────────────────────────────────

test('la page de contact pour une parcelle est accessible', function () {
    get(route('parcelles.contact.create', $this->parcelle))
        ->assertOk()
        ->assertSee('Contacter l\'agence')
        ->assertSee($this->parcelle->titre);
});

test('un visiteur peut soumettre un message pour une parcelle', function () {
    Notification::fake();

    post(route('parcelles.contact.store', $this->parcelle), [
        'name' => 'Marie Martin',
        'email' => 'marie@example.com',
        'phone' => '',
        'subject' => 'Question à propos de '.$this->parcelle->titre,
        'message' => 'Je voudrais plus d\'informations.',
    ])->assertRedirect(route('parcelles.show', $this->parcelle))
        ->assertSessionHas('success');

    expect(AgencyContact::where('contactable_id', $this->parcelle->id)
        ->where('contactable_type', Parcelle::class)
        ->exists()
    )->toBeTrue();

    Notification::assertSentTo($this->admin, AgencyContactNotification::class);
});

test('la soumission pour une parcelle échoue avec des données invalides', function () {
    post(route('parcelles.contact.store', $this->parcelle), [
        'name' => '',
        'email' => '',
        'message' => '',
    ])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
});

// ─── Notification ──────────────────────────────────────────────────────

test('la notification est envoyée uniquement aux utilisateurs avec la permission manage-properties', function () {
    Notification::fake();

    $userWithoutPermission = User::factory()->create();

    post(route('property.contact.store', $this->property), [
        'name' => 'Test',
        'email' => 'test@example.com',
        'subject' => 'Sujet',
        'message' => 'Message de test.',
    ]);

    Notification::assertSentTo($this->admin, AgencyContactNotification::class);
    Notification::assertNotSentTo($userWithoutPermission, AgencyContactNotification::class);
});

test('la notification est envoyée à tous les utilisateurs ayant la permission', function () {
    Notification::fake();

    $secondAdmin = User::factory()->create(['is_staff' => true, 'is_active' => true]);
    $secondAdmin->givePermissionTo('manage-properties');

    post(route('property.contact.store', $this->property), [
        'name' => 'Test',
        'email' => 'test@example.com',
        'subject' => 'Sujet',
        'message' => 'Message de test.',
    ]);

    Notification::assertSentTo([$this->admin, $secondAdmin], AgencyContactNotification::class);
});

// ─── Spam protection ───────────────────────────────────────────────────

test('le formulaire est protégé contre le spam avec throttle', function () {
    for ($i = 0; $i < 5; $i++) {
        post(route('property.contact.store', $this->property), [
            'name' => 'Test',
            'email' => 'test@example.com',
            'subject' => 'Sujet',
            'message' => 'Message.',
        ]);
    }

    post(route('property.contact.store', $this->property), [
        'name' => 'Test',
        'email' => 'test@example.com',
        'subject' => 'Sujet',
        'message' => 'Message.',
    ])->assertStatus(429);
});

// ─── XSS protection ────────────────────────────────────────────────────

test('les entrées sont protégées contre les attaques XSS', function () {
    post(route('property.contact.store', $this->property), [
        'name' => '<script>alert("xss")</script>',
        'email' => 'test@example.com',
        'subject' => '<script>alert("xss")</script>',
        'message' => '<script>alert("xss")</script>',
    ])->assertRedirect();

    $contact = AgencyContact::where('contactable_id', $this->property->id)
        ->where('contactable_type', Property::class)
        ->first();

    expect($contact->name)->toBe('<script>alert("xss")</script>');
    expect($contact->subject)->toBe('<script>alert("xss")</script>');
    expect($contact->message)->toBe('<script>alert("xss")</script>');
});