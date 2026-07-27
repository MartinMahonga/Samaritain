<?php

use App\Models\Contract;
use App\Models\Document;
use App\Models\Intervention;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\RentPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'owner', 'guard_name' => 'web']);

    $this->owner = User::factory()->create([
        'email_verified_at' => now(),
        'is_active' => true,
    ]);
    $this->owner->assignRole('owner');

    $this->properties = Property::factory()
        ->count(5)
        ->create(['created_by' => $this->owner->id]);

    // Create contracts for some properties
    $this->contract = Contract::create([
        'property_id' => $this->properties[0]->id,
        'tenant_name' => 'Jean Dupont',
        'tenant_email' => 'jean@example.com',
        'tenant_phone' => '+242061234567',
        'start_date' => now()->subMonths(3),
        'end_date' => now()->addMonths(9),
        'monthly_rent' => 200000,
        'deposit' => 200000,
        'status' => 'active',
        'created_by' => $this->owner->id,
    ]);

    // Create rent payments for the current month
    RentPayment::create([
        'contract_id' => $this->contract->id,
        'month' => now()->month,
        'year' => now()->year,
        'amount_due' => 200000,
        'amount_paid' => 200000,
        'due_date' => now()->startOfMonth(),
        'paid_at' => now(),
        'status' => 'paid',
    ]);

    // Create unpaid invoice
    Invoice::create([
        'property_id' => $this->properties[0]->id,
        'type' => 'electricity',
        'amount' => 45000,
        'due_date' => now()->subDays(10),
        'paid_at' => null,
        'status' => 'unpaid',
        'created_by' => $this->owner->id,
    ]);

    // Create interventions
    Intervention::create([
        'property_id' => $this->properties[0]->id,
        'title' => 'Fuite d\'eau',
        'description' => 'Fuite sous l\'évier de la salle de bain',
        'category' => 'plumbing',
        'urgency' => 'high',
        'status' => 'pending',
        'cost' => 0,
        'is_renovation' => false,
    ]);

    Intervention::create([
        'property_id' => $this->properties[1]->id,
        'title' => 'Peinture murale',
        'description' => 'Reprise de peinture dans le salon',
        'category' => 'painting',
        'urgency' => 'medium',
        'status' => 'in_progress',
        'cost' => 150000,
        'is_renovation' => false,
    ]);

    // Create documents
    Document::create([
        'property_id' => $this->properties[0]->id,
        'name' => 'Facture électricité juillet',
        'category' => 'invoice',
        'file_path' => 'documents/sample/placeholder.txt',
        'file_size' => 102400,
        'created_by' => $this->owner->id,
    ]);
});

test('owner can access dashboard without errors', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    $response->assertOk();
    $response->assertViewIs('pages.owner.dashboard');
});

test('dashboard displays correct property count', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    $response->assertViewHas('totalProperties', 5);
});

test('dashboard displays correct occupancy rate', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    // 1 out of 5 properties has an active contract = 20%
    $response->assertViewHas('occupancyRate', 20);
});

test('dashboard displays correct active contracts count', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    $response->assertViewHas('activeContractsCount', 1);
});

test('dashboard displays correct rent stats for current month', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    $response->assertViewHas('rentExpectedThisMonth', 200000)
        ->assertViewHas('rentCollectedThisMonth', 200000)
        ->assertViewHas('rentPendingThisMonth', 0);
});

test('dashboard displays correct intervention counts', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    $response->assertViewHas('totalInterventions', 2)
        ->assertViewHas('pendingInterventions', 2);
});

test('dashboard displays correct unpaid invoices sum', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    $response->assertViewHas('unpaidInvoicesSum', 45000);
});

test('dashboard includes recent interventions and documents', function () {
    $this->actingAs($this->owner);

    $response = $this->get(route('owner.dashboard'));

    $response->assertViewHas('recentInterventions')
        ->assertViewHas('recentDocuments');
});

test('non-owner user cannot access dashboard', function () {
    $regularUser = User::factory()->create([
        'email_verified_at' => now(),
        'is_active' => true,
    ]);

    $this->actingAs($regularUser);

    $response = $this->get(route('owner.dashboard'));

    $response->assertForbidden();
});
