<?php

use App\Enums\Owner\ContractStatus;
use App\Models\Contract;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'tenant', 'guard_name' => 'web']);

    $this->owner = User::factory()->create();
    $this->owner->assignRole('owner');

    $this->tenant = User::firstOrCreate(
        ['email' => 'tenant@example.com'],
        ['name' => 'Test Tenant']
    );
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
