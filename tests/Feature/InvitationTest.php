<?php

use App\Events\InvitationAccepted;
use App\Events\InvitationCancelled;
use App\Events\InvitationCreated;
use App\Models\AgencyInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(function () {
    // Créer les rôles nécessaires
    Role::create(['name' => 'owner', 'guard_name' => 'web']);
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'editor', 'guard_name' => 'web']);

    // Créer un propriétaire avec permission
    $this->owner = User::factory()->create([
        'is_staff' => true,
        'is_active' => true,
    ]);
    $this->owner->assignRole('owner');

    // Créer un admin avec permission manage-members
    $this->admin = User::factory()->create([
        'is_staff' => true,
        'is_active' => true,
    ]);
    $this->admin->assignRole('admin');
    // Note: permission 'manage-members' devrait être créée et assignée au rôle admin
});

// ─── Tests de création d'invitation ────────────────────────────────────

test('un propriétaire peut créer une invitation', function () {
    $role = Role::where('name', 'editor')->first();

    actingAs($this->owner)
        ->post(route('admin.invitations.store'), [
            'email' => 'test@example.com',
            'role_id' => $role->id,
        ])
        ->assertRedirect();

    assertDatabaseHas('agency_invitations', [
        'email' => 'test@example.com',
        'role_id' => $role->id,
        'accepted_at' => null,
    ]);
});

test('un admin peut créer une invitation', function () {
    $role = Role::where('name', 'editor')->first();

    actingAs($this->admin)
        ->post(route('admin.invitations.store'), [
            'email' => 'test@example.com',
            'role_id' => $role->id,
        ])
        ->assertRedirect();

    assertDatabaseHas('agency_invitations', [
        'email' => 'test@example.com',
    ]);
});

test('un utilisateur non staff ne peut pas créer une invitation', function () {
    $user = User::factory()->create(['is_staff' => false]);
    $role = Role::where('name', 'editor')->first();

    actingAs($user)
        ->post(route('admin.invitations.store'), [
            'email' => 'test@example.com',
            'role_id' => $role->id,
        ])
        ->assertForbidden();
});

test('ne peut pas inviter un email déjà membre', function () {
    $member = User::factory()->create([
        'email' => 'member@example.com',
        'is_staff' => true,
    ]);
    $role = Role::where('name', 'editor')->first();

    actingAs($this->owner)
        ->post(route('admin.invitations.store'), [
            'email' => 'member@example.com',
            'role_id' => $role->id,
        ])
        ->assertSessionHas('error');
});

test('ne peut pas créer une invitation pour le rôle owner', function () {
    $ownerRole = Role::where('name', 'owner')->first();

    actingAs($this->owner)
        ->post(route('admin.invitations.store'), [
            'email' => 'test@example.com',
            'role_id' => $ownerRole->id,
        ])
        ->assertSessionHasErrors('role_id');
});

test('ne peut pas créer deux invitations en attente pour le même email', function () {
    $role = Role::where('name', 'editor')->first();
    $email = 'duplicate@example.com';

    actingAs($this->owner)
        ->post(route('admin.invitations.store'), [
            'email' => $email,
            'role_id' => $role->id,
        ])
        ->assertRedirect();

    actingAs($this->owner)
        ->post(route('admin.invitations.store'), [
            'email' => $email,
            'role_id' => $role->id,
        ])
        ->assertSessionHas('error');
});

test('l\'event InvitationCreated est dispatché', function () {
    Event::fake();
    $role = Role::where('name', 'editor')->first();

    actingAs($this->owner)
        ->post(route('admin.invitations.store'), [
            'email' => 'event@example.com',
            'role_id' => $role->id,
        ]);

    Event::assertDispatched(InvitationCreated::class);
});

// ─── Tests d'acceptation d'invitation ──────────────────────────────────

test('peut accepter une invitation valide', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'newuser@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(7),
    ]);

    post(route('admin.invitations.accept'), [
        'token' => $invitation->token,
        'email' => 'newuser@example.com',
        'name' => 'Nouvel Utilisateur',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect();

    assertDatabaseHas('users', [
        'email' => 'newuser@example.com',
        'is_staff' => true,
    ]);

    assertDatabaseHas('agency_invitations', [
        'id' => $invitation->id,
        'accepted_at' => now(),
    ]);
});

test('ne peut pas accepter une invitation expirée', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'expired@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->subDay(),
    ]);

    post(route('admin.invitations.accept'), [
        'token' => $invitation->token,
        'email' => 'expired@example.com',
        'name' => 'Test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('token');
});

test('ne peut pas accepter une invitation déjà acceptée', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'already@example.com',
        'role_id' => $role->id,
        'accepted_at' => now(),
    ]);

    post(route('admin.invitations.accept'), [
        'token' => $invitation->token,
        'email' => 'already@example.com',
        'name' => 'Test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('token');
});

test('ne peut pas accepter avec un email différent de l\'invitation', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'correct@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(7),
    ]);

    post(route('admin.invitations.accept'), [
        'token' => $invitation->token,
        'email' => 'wrong@example.com',
        'name' => 'Test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');
});

test('l\'event InvitationAccepted est dispatché', function () {
    Event::fake();
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'acceptevent@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(7),
    ]);

    post(route('admin.invitations.accept'), [
        'token' => $invitation->token,
        'email' => 'acceptevent@example.com',
        'name' => 'Test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    Event::assertDispatched(InvitationAccepted::class);
});

// ─── Tests d'annulation d'invitation ───────────────────────────────────

test('un propriétaire peut annuler une invitation en attente', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'cancel@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(7),
    ]);

    actingAs($this->owner)
        ->delete(route('admin.invitations.destroy', $invitation))
        ->assertRedirect();

    expect($invitation->fresh()->cancelled_at)->not->toBeNull();
});

test('ne peut pas annuler une invitation déjà acceptée', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'alreadyaccepted@example.com',
        'role_id' => $role->id,
        'accepted_at' => now(),
    ]);

    actingAs($this->owner)
        ->delete(route('admin.invitations.destroy', $invitation))
        ->assertSessionHas('error');
});

test('l\'event InvitationCancelled est dispatché', function () {
    Event::fake();
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'cancelevent@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(7),
    ]);

    actingAs($this->owner)
        ->delete(route('admin.invitations.destroy', $invitation));

    Event::assertDispatched(InvitationCancelled::class);
});

// ─── Tests de renvoi d'invitation ─────────────────────────────────────

test('peut renvoyer une invitation en attente', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'resend@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(1),
    ]);

    actingAs($this->owner)
        ->post(route('admin.invitations.resend', $invitation))
        ->assertRedirect()
        ->assertSessionHas('success');
});

test('ne peut pas renvoyer une invitation expirée', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'expiredresend@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->subDay(),
    ]);

    actingAs($this->owner)
        ->post(route('admin.invitations.resend', $invitation))
        ->assertSessionHas('error');
});

// ─── Tests de listing ──────────────────────────────────────────────────

test('un propriétaire peut voir la liste des invitations', function () {
    AgencyInvitation::factory()->count(3)->create();

    actingAs($this->owner)
        ->get(route('admin.invitations.index'))
        ->assertOk();
});

test('un utilisateur non staff ne peut pas voir la liste des invitations', function () {
    $user = User::factory()->create(['is_staff' => false]);

    actingAs($user)
        ->get(route('admin.invitations.index'))
        ->assertForbidden();
});

// ─── Tests de validation ───────────────────────────────────────────────

test('la validation échoue si le mot de passe est trop court', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'validation@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(7),
    ]);

    post(route('admin.invitations.accept'), [
        'token' => $invitation->token,
        'email' => 'validation@example.com',
        'name' => 'Test',
        'password' => 'short',
        'password_confirmation' => 'short',
    ])->assertSessionHasErrors('password');
});

test('la validation échoue si l\'email est manquant dans l\'acceptation', function () {
    $role = Role::where('name', 'editor')->first();
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'missingemail@example.com',
        'role_id' => $role->id,
        'expires_at' => now()->addDays(7),
    ]);

    post(route('admin.invitations.accept'), [
        'token' => $invitation->token,
        'name' => 'Test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');
});

// ─── Tests du modèle ──────────────────────────────────────────────────

test('isValid retourne true pour une invitation valide', function () {
    $invitation = AgencyInvitation::factory()->make([
        'expires_at' => now()->addDays(7),
        'accepted_at' => null,
        'cancelled_at' => null,
    ]);

    expect($invitation->isValid())->toBeTrue();
});

test('isValid retourne false pour une invitation expirée', function () {
    $invitation = AgencyInvitation::factory()->make([
        'expires_at' => now()->subDay(),
        'accepted_at' => null,
        'cancelled_at' => null,
    ]);

    expect($invitation->isValid())->toBeFalse();
});

test('isValid retourne false pour une invitation acceptée', function () {
    $invitation = AgencyInvitation::factory()->make([
        'expires_at' => now()->addDays(7),
        'accepted_at' => now(),
        'cancelled_at' => null,
    ]);

    expect($invitation->isValid())->toBeFalse();
});

test('isValid retourne false pour une invitation annulée', function () {
    $invitation = AgencyInvitation::factory()->make([
        'expires_at' => now()->addDays(7),
        'accepted_at' => null,
        'cancelled_at' => now(),
    ]);

    expect($invitation->isValid())->toBeFalse();
});

test('l\'email est normalisé en minuscules', function () {
    $invitation = AgencyInvitation::factory()->create([
        'email' => 'Test@Example.COM',
    ]);

    expect($invitation->email)->toBe('test@example.com');
});

test('scope pending retourne les invitations en attente valides', function () {
    AgencyInvitation::factory()->create(['email' => 'valid@example.com', 'expires_at' => now()->addDays(7)]);
    AgencyInvitation::factory()->create(['email' => 'expired@example.com', 'expires_at' => now()->subDay()]);
    AgencyInvitation::factory()->create(['email' => 'accepted@example.com', 'accepted_at' => now()]);
    AgencyInvitation::factory()->create(['email' => 'cancelled@example.com', 'cancelled_at' => now()]);

    $pending = AgencyInvitation::pending()->get();

    expect($pending)->toHaveCount(1);
    expect($pending->first()->email)->toBe('valid@example.com');
});
