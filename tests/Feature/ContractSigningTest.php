<?php

use App\Enums\Owner\ContractStatus;
use App\Events\ContractFullySigned;
use App\Models\Contract;
use App\Models\Conversation;
use App\Models\Property;
use App\Models\User;
use App\Notifications\ContractSigningRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'tenant', 'guard_name' => 'web']);

    $this->owner = User::factory()->create(['email_verified_at' => now()]);
    $this->owner->assignRole('owner');

    $this->tenant = User::factory()->create([
        'email' => 'tenant@example.com',
        'email_verified_at' => now(),
    ]);
    $this->tenant->assignRole('tenant');

    $this->property = Property::factory()->create(['created_by' => $this->owner->id]);
});

it('allows owner to sign pending contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::PENDING_OWNER_SIGNATURE->value,
    ]);

    actingAs($this->owner)
        ->post(route('owner.contracts.sign', $contract), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ])
        ->assertRedirect(route('owner.contracts.show', $contract));

    $contract->refresh();
    expect($contract->status)->toBe(ContractStatus::PENDING_TENANT_SIGNATURE->value);
    expect($contract->owner_signed_at)->not->toBeNull();
});

it('allows tenant to sign pending contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::PENDING_TENANT_SIGNATURE->value,
    ]);

    actingAs($this->tenant)
        ->post(route('tenant.contracts.sign', $contract), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ])
        ->assertRedirect(route('tenant.contracts.show', $contract));

    $contract->refresh();
    expect($contract->status)->toBe(ContractStatus::ACTIVE->value);
    expect($contract->tenant_signed_at)->not->toBeNull();
});

it('prevents double signing', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::PENDING_OWNER_SIGNATURE->value,
    ]);

    actingAs($this->owner)
        ->post(route('owner.contracts.sign', $contract), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ])
        ->assertRedirect();

    actingAs($this->owner)
        ->post(route('owner.contracts.sign', $contract), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ])
        ->assertStatus(403);
});

it('allows authenticated user without owner role to access owner portal', function () {
    $userWithoutRole = User::factory()->create(['email_verified_at' => now()]);

    actingAs($userWithoutRole)
        ->get(route('owner.dashboard'))
        ->assertOk();
});

it('allows authenticated user without owner role to create a contract', function () {
    $userWithoutRole = User::factory()->create(['email_verified_at' => now()]);
    Property::factory()->create(['created_by' => $userWithoutRole->id]);

    actingAs($userWithoutRole)
        ->get(route('owner.contracts.create'))
        ->assertOk();
});

it('sends notification to tenant when contract is created', function () {
    Notification::fake();

    $contractData = [
        'property_id' => $this->property->id,
        'tenant_name' => 'Jean Dupont',
        'tenant_email' => $this->tenant->email,
        'tenant_phone' => '0600000000',
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
        'monthly_rent' => 150000,
        'deposit' => 300000,
        'status' => 'active',
    ];

    actingAs($this->owner)
        ->post(route('owner.contracts.store'), $contractData)
        ->assertRedirect();

    Notification::assertSentTo($this->tenant, ContractSigningRequestNotification::class);
});

it('sends mail notification to tenant without account when contract is created', function () {
    Notification::fake();

    $contractData = [
        'property_id' => $this->property->id,
        'tenant_name' => 'Pierre Durand',
        'tenant_email' => 'pierre.durand@example.com',
        'tenant_phone' => '0600000000',
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
        'monthly_rent' => 150000,
        'deposit' => 300000,
        'status' => 'active',
    ];

    actingAs($this->owner)
        ->post(route('owner.contracts.store'), $contractData)
        ->assertRedirect();

    Notification::assertSentTo(
        new AnonymousNotifiable(['mail' => 'pierre.durand@example.com']),
        ContractSigningRequestNotification::class
    );
});

it('sends only one notification per contract creation', function () {
    Notification::fake();

    $contractData = [
        'property_id' => $this->property->id,
        'tenant_name' => 'Jean Dupont',
        'tenant_email' => $this->tenant->email,
        'tenant_phone' => '0600000000',
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
        'monthly_rent' => 150000,
        'deposit' => 300000,
        'status' => 'active',
    ];

    actingAs($this->owner)
        ->post(route('owner.contracts.store'), $contractData)
        ->assertRedirect();

    Notification::assertSentToTimes($this->tenant, ContractSigningRequestNotification::class, 1);
});

it('creates a conversation when contract becomes fully signed', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::PENDING_TENANT_SIGNATURE->value,
        'owner_signed_at' => now(),
    ]);

    actingAs($this->tenant)
        ->post(route('tenant.contracts.sign', $contract), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ])
        ->assertRedirect();

    $contract->refresh();
    expect($contract->status)->toBe(ContractStatus::ACTIVE->value);

    $conversation = Conversation::where('contract_id', $contract->id)->first();
    expect($conversation)->not->toBeNull();
    expect($conversation->owner_id)->toBe($this->owner->id);
    expect($conversation->tenant_id)->toBe($this->tenant->id);
});

it('does not create duplicate conversation when event fires twice', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::PENDING_TENANT_SIGNATURE->value,
        'owner_signed_at' => now(),
    ]);

    actingAs($this->tenant)
        ->post(route('tenant.contracts.sign', $contract), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        ])
        ->assertRedirect();

    // Manually dispatch the event again to simulate a double fire
    event(new ContractFullySigned($contract->refresh()));

    $count = Conversation::where('contract_id', $contract->id)->count();
    expect($count)->toBe(1);
});

it('allows owner to access their messenger page', function () {
    actingAs($this->owner)
        ->get(route('owner.messenger'))
        ->assertOk();
});

it('allows tenant to access their messenger page', function () {
    // Ensure tenant has a contract so the tenant middleware passes
    Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::ACTIVE->value,
    ]);

    actingAs($this->tenant)
        ->get(route('tenant.messenger'))
        ->assertOk();
});
