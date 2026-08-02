<?php

use App\Enums\Owner\ContractStatus;
use App\Models\Contract;
use App\Models\Property;
use App\Models\RentPayment;
use App\Models\User;
use App\Notifications\ContractCancelledNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

it('allows owner to cancel a draft contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::DRAFT->value,
    ]);

    actingAs($this->owner)
        ->post(route('owner.contracts.cancel', $contract))
        ->assertRedirect(route('owner.contracts.show', $contract));

    $contract->refresh();
    expect($contract->status)->toBe(ContractStatus::CANCELLED->value);
    expect($contract->cancelled_at)->not->toBeNull();
});

it('allows owner to cancel a pending owner signature contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::PENDING_OWNER_SIGNATURE->value,
    ]);

    actingAs($this->owner)
        ->post(route('owner.contracts.cancel', $contract))
        ->assertRedirect(route('owner.contracts.show', $contract));

    $contract->refresh();
    expect($contract->status)->toBe(ContractStatus::CANCELLED->value);
});

it('allows owner to cancel an active contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::ACTIVE->value,
    ]);

    actingAs($this->owner)
        ->post(route('owner.contracts.cancel', $contract))
        ->assertRedirect(route('owner.contracts.show', $contract));

    $contract->refresh();
    expect($contract->status)->toBe(ContractStatus::CANCELLED->value);
});

it('prevents owner from cancelling an already cancelled contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::CANCELLED->value,
        'cancelled_at' => now(),
    ]);

    actingAs($this->owner)
        ->post(route('owner.contracts.cancel', $contract))
        ->assertStatus(403);
});

it('prevents non-owner from cancelling a contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::DRAFT->value,
    ]);

    actingAs($this->tenant)
        ->post(route('owner.contracts.cancel', $contract))
        ->assertStatus(403);
});

it('sends notification to tenant when contract is cancelled', function () {
    Notification::fake();

    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::ACTIVE->value,
    ]);

    actingAs($this->owner)
        ->post(route('owner.contracts.cancel', $contract))
        ->assertRedirect();

    Notification::assertSentTo($this->tenant, ContractCancelledNotification::class);
});

it('allows owner to delete a draft contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::DRAFT->value,
    ]);

    actingAs($this->owner)
        ->delete(route('owner.contracts.destroy', $contract))
        ->assertRedirect(route('owner.contracts.index'));

    expect(Contract::find($contract->id))->toBeNull();
});

it('allows owner to delete a cancelled contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::CANCELLED->value,
        'cancelled_at' => now(),
    ]);

    actingAs($this->owner)
        ->delete(route('owner.contracts.destroy', $contract))
        ->assertRedirect(route('owner.contracts.index'));

    expect(Contract::find($contract->id))->toBeNull();
});

it('prevents owner from deleting an active contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::ACTIVE->value,
    ]);

    actingAs($this->owner)
        ->delete(route('owner.contracts.destroy', $contract))
        ->assertStatus(403);
});

it('prevents owner from deleting a pending signature contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::PENDING_OWNER_SIGNATURE->value,
    ]);

    actingAs($this->owner)
        ->delete(route('owner.contracts.destroy', $contract))
        ->assertStatus(403);
});

it('prevents non-owner from deleting a contract', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::DRAFT->value,
    ]);

    actingAs($this->tenant)
        ->delete(route('owner.contracts.destroy', $contract))
        ->assertStatus(403);
});

it('deletes rent payments when contract is deleted', function () {
    $contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'status' => ContractStatus::DRAFT->value,
    ]);

    RentPayment::create([
        'contract_id' => $contract->id,
        'month' => 1,
        'year' => 2026,
        'amount_due' => 150000,
        'amount_paid' => 0,
        'due_date' => '2026-01-01',
        'status' => 'unpaid',
    ]);

    actingAs($this->owner)
        ->delete(route('owner.contracts.destroy', $contract))
        ->assertRedirect();

    expect(RentPayment::where('contract_id', $contract->id)->count())->toBe(0);
});
