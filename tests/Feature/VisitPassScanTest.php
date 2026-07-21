<?php

use App\Models\Property;
use App\Models\User;
use App\Models\VisitPass;
use App\Services\PassScanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->scanner = User::factory()->create(['is_staff' => true, 'is_active' => true]);
    $this->property = Property::factory()->create();
    $this->visitPass = VisitPass::create([
        'uuid' => (string) Str::uuid(),
        'user_id' => User::factory()->create()->id,
        'property_id' => $this->property->id,
        'holder_name' => 'Jean Dupont',
        'phone' => '+242061234567',
        'amount' => 5000,
        'allowed_visits' => VisitPass::ALLOWED_VISITS,
        'remaining_visits' => VisitPass::ALLOWED_VISITS,
        'payment_status' => 'paid',
        'status' => 'active',
        'paid_at' => now(),
        'expires_at' => now()->addDays(3),
    ]);
});

test('un pass visite est créé avec 3 visites autorisées', function () {
    $visitPass = VisitPass::create([
        'uuid' => (string) Str::uuid(),
        'user_id' => User::factory()->create()->id,
        'property_id' => $this->property->id,
        'holder_name' => 'Marie Martin',
        'phone' => '+242069876543',
        'amount' => 5000,
        'payment_status' => 'pending',
        'status' => 'pending_payment',
    ]);

    expect($visitPass->allowed_visits)->toBe(3)
        ->and($visitPass->remaining_visits)->toBe(3);
});

test('chaque scan décrémente le nombre de visites restantes', function () {
    $service = app(PassScanService::class);
    $request = Request::create('/scan/process', 'POST');

    $service->scanPass($this->visitPass, $this->scanner, $request);

    $this->visitPass->refresh();

    expect($this->visitPass->remaining_visits)->toBe(2)
        ->and($this->visitPass->status)->toBe('active');
});

test('le pass visite devient utilisé après 3 scans', function () {
    $service = app(PassScanService::class);
    $request = Request::create('/scan/process', 'POST');

    foreach (range(1, 3) as $scan) {
        $service->scanPass($this->visitPass->fresh(), $this->scanner, $request);
    }

    $this->visitPass->refresh();

    expect($this->visitPass->remaining_visits)->toBe(0)
        ->and($this->visitPass->status)->toBe('used')
        ->and($this->visitPass->isUsed())->toBeTrue();
});

test('un pass visite sans visites restantes est rejeté au scan', function () {
    $this->visitPass->update([
        'remaining_visits' => 0,
        'status' => 'used',
    ]);

    $result = app(PassScanService::class)->validatePass($this->visitPass->uuid);

    expect($result['valid'])->toBeFalse()
        ->and($result['reason'])->toBe('used')
        ->and($result['message'])->toBe('Plus aucune visite disponible');
});

test('le scan via API retourne les visites restantes', function () {
    $this->actingAs($this->scanner);

    $response = $this->postJson(route('scan.process'), [
        'uuid' => $this->visitPass->uuid,
    ]);

    $response->assertOk()
        ->assertJson([
            'valid' => true,
            'pass' => [
                'remaining_visits' => 2,
                'allowed_visits' => 3,
            ],
        ]);
});
