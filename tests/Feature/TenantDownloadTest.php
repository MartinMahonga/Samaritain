<?php

use App\Enums\Owner\ContractStatus;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'tenant', 'guard_name' => 'web']);

    Storage::fake('local');

    $this->owner = User::factory()->create(['email_verified_at' => now()]);
    $this->owner->assignRole('owner');

    $this->tenant = User::factory()->create([
        'email' => 'tenant@example.com',
        'email_verified_at' => now(),
    ]);
    $this->tenant->assignRole('tenant');

    $this->property = Property::factory()->create(['created_by' => $this->owner->id]);

    $this->contract = Contract::factory()->create([
        'property_id' => $this->property->id,
        'created_by' => $this->owner->id,
        'tenant_email' => $this->tenant->email,
        'tenant_name' => 'Jean Dupont',
        'status' => ContractStatus::ACTIVE->value,
    ]);
});

it('allows tenant to download their contract PDF', function () {
    actingAs($this->tenant)
        ->get(route('tenant.contracts.pdf', $this->contract))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('prevents tenant from downloading another tenant contract PDF', function () {
    $otherTenant = User::factory()->create([
        'email' => 'other-tenant@example.com',
        'email_verified_at' => now(),
    ]);
    $otherTenant->assignRole('tenant');

    actingAs($otherTenant)
        ->get(route('tenant.contracts.pdf', $this->contract))
        ->assertForbidden();
});

it('allows tenant to download a document linked to their property', function () {
    $file = UploadedFile::fake()->create('receipt.pdf', 100, 'application/pdf');
    $path = Storage::disk('local')->putFileAs('documents/receipts', $file, 'receipt.pdf');

    $document = Document::create([
        'property_id' => $this->property->id,
        'name' => 'Reçu de loyer',
        'category' => 'receipt',
        'file_path' => $path,
        'file_size' => 100,
        'created_by' => $this->owner->id,
    ]);

    actingAs($this->tenant)
        ->get(route('tenant.documents.download', $document))
        ->assertOk();
});

it('prevents tenant from downloading a document not linked to their property', function () {
    $otherProperty = Property::factory()->create(['created_by' => $this->owner->id]);

    $file = UploadedFile::fake()->create('other-doc.pdf', 100, 'application/pdf');
    $path = Storage::disk('local')->putFileAs('documents/other', $file, 'other-doc.pdf');

    $document = Document::create([
        'property_id' => $otherProperty->id,
        'name' => 'Document privé',
        'category' => 'other',
        'file_path' => $path,
        'file_size' => 100,
        'created_by' => $this->owner->id,
    ]);

    actingAs($this->tenant)
        ->get(route('tenant.documents.download', $document))
        ->assertForbidden();
});
