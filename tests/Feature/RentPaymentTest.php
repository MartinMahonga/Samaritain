<?php

use App\Jobs\ProcessPawaPayCallback;
use App\Models\Contract;
use App\Models\Property;
use App\Models\RentPayment;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PawapayService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function rentPaymentScenario(bool $withReceiptPdf = false): array
{
    $owner = User::factory()->create();
    $tenant = User::factory()->create();
    $property = Property::factory()->create(['created_by' => $owner->id]);
    $contract = Contract::factory()->create([
        'property_id' => $property->id,
        'tenant_email' => $tenant->email,
        'tenant_name' => 'Jean Dupont',
        'status' => 'active',
        'monthly_rent' => 150000,
        'start_date' => now()->startOfMonth(),
        'created_by' => $owner->id,
    ]);
    $rentPayment = RentPayment::factory()->create([
        'contract_id' => $contract->id,
        'amount_due' => 150000,
        'amount_paid' => 0,
        'month' => now()->month,
        'year' => now()->year,
        'status' => 'unpaid',
    ]);

    if ($withReceiptPdf) {
        // The receipt view relies on auth()->user() (the owner). From a queued
        // callback there is no authenticated request, so we stub the PDF
        // facade and point the default disk at a faked local disk to keep the
        // test focused on the payment state transition.
        config(['filesystems.default' => 'local']);
        Storage::fake('local');

        Pdf::shouldReceive('loadView')->once()->andReturnSelf();
        Pdf::shouldReceive('output')->once()->andReturn('fake-pdf');
    }

    return compact('owner', 'tenant', 'property', 'contract', 'rentPayment');
}

function paymentPageHttpFake(): void
{
    Http::fake([
        'api.sandbox.pawapay.io/v2/paymentpage' => Http::response([
            'depositId' => 'some-uuid',
            'status' => 'ACCEPTED',
            'redirectUrl' => 'https://api.sandbox.pawapay.io/payment-page/xyz',
        ], 200),
    ]);
}

/*
|--------------------------------------------------------------------------
| Initiation du paiement de loyer (Tenant\DashboardController::payRentPayment)
|--------------------------------------------------------------------------
*/

test('le locataire peut initier le paiement de son loyer et est redirigé vers pawaPay', function () {
    $s = rentPaymentScenario();
    paymentPageHttpFake();

    $this->actingAs($s['tenant'])
        ->post(route('tenant.rent-payments.pay', $s['rentPayment']))
        ->assertRedirect('https://api.sandbox.pawapay.io/payment-page/xyz');

    $transaction = Transaction::where('user_id', $s['tenant']->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->rent_payment_id)->toBe($s['rentPayment']->id)
        ->and($transaction->amount)->toBe(150000)
        ->and($transaction->status)->toBe('accepted')
        ->and($transaction->currency)->toBe('XAF')
        ->and($transaction->deposit_id)->not->toBeNull();

    $s['rentPayment']->refresh();
    expect($s['rentPayment']->transaction_id)->toBe($transaction->transaction_id);

    // Le depositId persisté est bien celui envoyé à pawaPay (clé d'idempotence).
    Http::assertSent(fn ($request) => str_contains($request->url(), '/v2/paymentpage')
        && $request['depositId'] === $transaction->deposit_id);
});

test('un loyer déjà payé ne peut pas être re-payé', function () {
    $s = rentPaymentScenario();
    $s['rentPayment']->update(['status' => 'paid', 'amount_paid' => 150000, 'paid_at' => now()]);

    $this->actingAs($s['tenant'])
        ->post(route('tenant.rent-payments.pay', $s['rentPayment']))
        ->assertRedirect(route('tenant.payments'));

    expect(Transaction::count())->toBe(0);
});

test('un locataire ne peut pas payer le loyer d\'un autre contrat', function () {
    $s = rentPaymentScenario();
    $otherTenant = User::factory()->create();
    $otherContract = Contract::factory()->create([
        'tenant_email' => $otherTenant->email,
        'status' => 'active',
        'created_by' => $s['owner']->id,
    ]);
    $otherRent = RentPayment::factory()->create(['contract_id' => $otherContract->id]);

    $this->actingAs($s['tenant'])
        ->post(route('tenant.rent-payments.pay', $otherRent))
        ->assertForbidden();

    expect(Transaction::count())->toBe(0);
});

test('si l\'API pawaPay échoue, la transaction reste en pending (jamais failed)', function () {
    $s = rentPaymentScenario();

    Http::fake([
        'api.sandbox.pawapay.io/v2/paymentpage' => Http::response('Server error', 500),
    ]);

    $this->actingAs($s['tenant'])
        ->post(route('tenant.rent-payments.pay', $s['rentPayment']))
        ->assertRedirect(route('tenant.payments'));

    $transaction = Transaction::where('user_id', $s['tenant']->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->status)->toBe('pending');
});
/*
|--------------------------------------------------------------------------
| Callback → marquage du loyer comme payé
|--------------------------------------------------------------------------
*/

test('le callback COMPLETED marque le loyer comme payé', function () {
    $s = rentPaymentScenario(withReceiptPdf: true);
    $depositId = (string) Str::uuid();

    $transaction = Transaction::factory()->create([
        'user_id' => $s['tenant']->id,
        'rent_payment_id' => $s['rentPayment']->id,
        'status' => 'pending',
        'amount' => 150000,
        'deposit_id' => $depositId,
        'currency' => 'XAF',
    ]);
    $s['rentPayment']->update(['transaction_id' => $transaction->transaction_id]);

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'COMPLETED',
        ], 200),
    ]);

    $job = new ProcessPawaPayCallback($transaction, ['status' => 'COMPLETED']);
    $job->handle(app(PawapayService::class));

    $transaction->refresh();
    $s['rentPayment']->refresh();

    expect($transaction->status)->toBe('completed')
        ->and($s['rentPayment']->status)->toBe('paid')
        ->and($s['rentPayment']->amount_paid)->toBe(150000)
        ->and($s['rentPayment']->paid_at)->not->toBeNull();
});

test('le callback est idempotent : un loyer déjà payé reste payé', function () {
    $s = rentPaymentScenario();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::factory()->create([
        'user_id' => $s['tenant']->id,
        'rent_payment_id' => $s['rentPayment']->id,
        'status' => 'pending',
        'amount' => 150000,
        'deposit_id' => $depositId,
        'currency' => 'XAF',
    ]);
    $s['rentPayment']->update([
        'status' => 'paid',
        'amount_paid' => 150000,
        'paid_at' => now(),
        'transaction_id' => $transaction->transaction_id,
    ]);

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'COMPLETED',
        ], 200),
    ]);

    $job = new ProcessPawaPayCallback($transaction, ['status' => 'COMPLETED']);
    $job->handle(app(PawapayService::class));

    $s['rentPayment']->refresh();
    expect($s['rentPayment']->status)->toBe('paid')
        ->and($s['rentPayment']->amount_paid)->toBe(150000);
});

test('le callback FAILED laisse le loyer impayé', function () {
    $s = rentPaymentScenario();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::factory()->create([
        'user_id' => $s['tenant']->id,
        'rent_payment_id' => $s['rentPayment']->id,
        'status' => 'pending',
        'amount' => 150000,
        'deposit_id' => $depositId,
        'currency' => 'XAF',
    ]);
    $s['rentPayment']->update(['transaction_id' => $transaction->transaction_id]);

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'FAILED',
        ], 200),
    ]);

    $job = new ProcessPawaPayCallback($transaction, ['status' => 'FAILED']);
    $job->handle(app(PawapayService::class));

    $transaction->refresh();
    $s['rentPayment']->refresh();

    expect($transaction->status)->toBe('failed')
        ->and($s['rentPayment']->status)->toBe('unpaid');
});

test('la réconciliation marque le loyer comme payé quand le dépôt est COMPLETED', function () {
    $s = rentPaymentScenario(withReceiptPdf: true);
    $depositId = (string) Str::uuid();

    $transaction = Transaction::factory()->create([
        'user_id' => $s['tenant']->id,
        'rent_payment_id' => $s['rentPayment']->id,
        'status' => 'pending',
        'amount' => 150000,
        'deposit_id' => $depositId,
        'currency' => 'XAF',
    ]);
    $s['rentPayment']->update(['transaction_id' => $transaction->transaction_id]);

    $transaction->created_at = now()->subHour();
    $transaction->updated_at = now()->subHour();
    $transaction->save();

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'COMPLETED',
        ], 200),
    ]);

    $this->artisan('pawapay:reconcile')->assertExitCode(0);

    $s['rentPayment']->refresh();
    expect($s['rentPayment']->status)->toBe('paid')
        ->and($s['rentPayment']->paid_at)->not->toBeNull();
});

/*
|--------------------------------------------------------------------------
| Relations du modèle
|--------------------------------------------------------------------------
*/

test('une transaction est reliée à un loyer et le loyer à sa transaction', function () {
    $s = rentPaymentScenario();
    $transaction = Transaction::factory()->create([
        'user_id' => $s['tenant']->id,
        'rent_payment_id' => $s['rentPayment']->id,
        'amount' => 150000,
        'deposit_id' => (string) Str::uuid(),
    ]);
    $s['rentPayment']->update(['transaction_id' => $transaction->transaction_id]);

    expect($transaction->rentPayment->id)->toBe($s['rentPayment']->id)
        ->and($s['rentPayment']->transaction->transaction_id)->toBe($transaction->transaction_id);
});
