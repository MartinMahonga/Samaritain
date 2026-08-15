<?php

use App\Exceptions\PawaPayException;
use App\Jobs\ProcessPawaPayCallback;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VisitPass;
use App\Services\PawapayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Tests d'unité du PawapayService
|--------------------------------------------------------------------------
*/

test('predict_provider retourne le provider et le msisdn normalisés', function () {
    config(['services.pawapay.base_url' => 'https://api.sandbox.pawapay.io']);
    config(['services.pawapay.token' => 'test-token']);

    Http::fake([
        'api.sandbox.pawapay.io/v2/predict-provider' => Http::response([
            'predictedProvider' => 'MTN_MOMO_COG',
            'msisdn' => '+242061234567',
        ], 200),
    ]);

    $service = new PawapayService;
    $result = $service->predictProvider('+242061234567');

    expect($result['predictedProvider'])->toBe('MTN_MOMO_COG')
        ->and($result['msisdn'])->toBe('+242061234567');
});

test('predict_provider lance une exception si l\'API échoue', function () {
    config(['services.pawapay.base_url' => 'https://api.sandbox.pawapay.io']);
    config(['services.pawapay.token' => 'test-token']);

    Http::fake([
        'api.sandbox.pawapay.io/v2/predict-provider' => Http::response('Error', 500),
    ]);

    $service = new PawapayService;

    expect(fn () => $service->predictProvider('+242061234567'))
        ->toThrow(PawaPayException::class, 'Impossible de prédire le fournisseur');
});

test('get_active_configuration retourne les providers et décimales', function () {
    config(['services.pawapay.base_url' => 'https://api.sandbox.pawapay.io']);
    config(['services.pawapay.token' => 'test-token']);

    Http::fake([
        'api.sandbox.pawapay.io/v2/active-configuration' => Http::response([
            'country' => 'COG',
            'currency' => 'XAF',
            'decimalsInAmount' => 'NONE',
            'providers' => ['MTN_MOMO_COG', 'AIRTEL_COG'],
        ], 200),
    ]);

    $service = new PawapayService;
    $result = $service->getActiveConfiguration();

    expect($result['country'])->toBe('COG')
        ->and($result['currency'])->toBe('XAF')
        ->and($result['decimalsInAmount'])->toBe('NONE')
        ->and($result['providers'])->toMatchArray(['MTN_MOMO_COG', 'AIRTEL_COG']);
});

test('create_deposit envoie le depositId et retourne ACCEPTED', function () {
    config(['services.pawapay.base_url' => 'https://api.sandbox.pawapay.io']);
    config(['services.pawapay.token' => 'test-token']);

    $depositId = (string) Str::uuid();

    Http::fake([
        'api.sandbox.pawapay.io/v2/deposits' => Http::response([
            'depositId' => $depositId,
            'status' => 'ACCEPTED',
            'provider' => 'MTN_MOMO_COG',
            'amountDetails' => ['amount' => '5000', 'currency' => 'XAF'],
        ], 200),
    ]);

    $service = new PawapayService;
    $result = $service->createDeposit($depositId, [
        'amountDetails' => ['amount' => '5000', 'currency' => 'XAF'],
        'provider' => 'MTN_MOMO_COG',
    ]);

    expect($result['status'])->toBe('ACCEPTED')
        ->and($result['depositId'])->toBe($depositId);

    Http::assertSent(function ($request) use ($depositId) {
        return $request->hasHeader('Authorization', 'Bearer test-token')
            && $request['depositId'] === $depositId
            && $request['provider'] === 'MTN_MOMO_COG';
    });
});

test('create_deposit lance une exception en cas d\'erreur API', function () {
    config(['services.pawapay.base_url' => 'https://api.sandbox.pawapay.io']);
    config(['services.pawapay.token' => 'test-token']);

    Http::fake([
        'api.sandbox.pawapay.io/v2/deposits' => Http::response('Bad request', 400),
    ]);

    $service = new PawapayService;

    expect(fn () => $service->createDeposit((string) Str::uuid(), [
        'amountDetails' => ['amount' => '5000', 'currency' => 'XAF'],
    ]))->toThrow(PawaPayException::class);
});

test('get_deposit_status retourne NOT_FOUND pour une réponse 404', function () {
    config(['services.pawapay.base_url' => 'https://api.sandbox.pawapay.io']);
    config(['services.pawapay.token' => 'test-token']);

    $depositId = (string) Str::uuid();

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response('', 404),
    ]);

    $service = new PawapayService;
    $result = $service->getDepositStatus($depositId);

    expect($result['status'])->toBe('NOT_FOUND')
        ->and($result['depositId'])->toBe($depositId);
});

test('get_deposit_status retourne COMPLETED pour une réponse 200', function () {
    config(['services.pawapay.base_url' => 'https://api.sandbox.pawapay.io']);
    config(['services.pawapay.token' => 'test-token']);

    $depositId = (string) Str::uuid();

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'COMPLETED',
            'provider' => 'MTN_MOMO_COG',
        ], 200),
    ]);

    $service = new PawapayService;
    $result = $service->getDepositStatus($depositId);

    expect($result['status'])->toBe('COMPLETED');
});

test('verify_callback_signature retourne true sans secret configuré', function () {
    config([
        'services.pawapay.callback_secret' => null,
        'services.pawapay.verify_callback_signature' => true,
    ]);

    $service = new PawapayService;

    expect($service->verifyCallbackSignature('payload', 'signature'))->toBeTrue();
});

test('verify_callback_signature retourne true avec une signature valide', function () {
    $secret = 'my-secret-key';
    config([
        'services.pawapay.callback_secret' => $secret,
        'services.pawapay.verify_callback_signature' => true,
    ]);

    $service = new PawapayService;
    $payload = '{"depositId":"test"}';
    $validSignature = hash_hmac('sha256', $payload, $secret);

    expect($service->verifyCallbackSignature($payload, $validSignature))->toBeTrue();
});

test('verify_callback_signature retourne false avec une signature invalide', function () {
    $secret = 'my-secret-key';
    config([
        'services.pawapay.callback_secret' => $secret,
        'services.pawapay.verify_callback_signature' => true,
    ]);

    $service = new PawapayService;
    $payload = '{"depositId":"test"}';

    expect($service->verifyCallbackSignature($payload, 'invalid-signature'))->toBeFalse();
});

/*
|--------------------------------------------------------------------------
| Tests du flow de paiement (TransactionController)
|--------------------------------------------------------------------------
*/

test('payment_page crée une transaction avec deposit_id et redirige vers pawaPay', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
        'services.pawapay.callback_secret' => 'secret',
    ]);

    $user = User::factory()->create();

    Http::fake([
        'api.sandbox.pawapay.io/v2/paymentpage' => Http::response([
            'depositId' => 'some-uuid',
            'status' => 'ACCEPTED',
            'redirectUrl' => 'https://api.sandbox.pawapay.io/payment-page/xyz',
        ], 200),
    ]);

    $this->actingAs($user)
        ->get(route('transactions.pay'))
        ->assertRedirect('https://api.sandbox.pawapay.io/payment-page/xyz');

    $transaction = Transaction::where('user_id', $user->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->deposit_id)->not->toBeNull()
        ->and($transaction->status)->toBe('accepted')
        ->and($transaction->currency)->toBe('XAF');
});

test('payment_page laisse la transaction en pending si l\'API pawaPay échoue', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    $user = User::factory()->create();

    Http::fake([
        'api.sandbox.pawapay.io/v2/paymentpage' => Http::response('Server error', 500),
    ]);

    $this->actingAs($user)
        ->get(route('transactions.pay'))
        ->assertRedirect();

    $transaction = Transaction::where('user_id', $user->id)->first();

    // Critical rule: on HTTP error, do NOT mark as failed — keep as pending
    expect($transaction->status)->toBe('pending');
});

test('payment_page nécessite une authentification', function () {
    $this->get(route('transactions.pay'))
        ->assertRedirect(route('login'));
});

/*
|--------------------------------------------------------------------------
| Tests du flow visit pass → paiement (UserVisitPassController)
|--------------------------------------------------------------------------
*/

test('store visit pass crée le pass et redirige vers la page de paiement pawaPay', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    Http::fake([
        'api.sandbox.pawapay.io/v2/paymentpage' => Http::response([
            'depositId' => 'some-uuid',
            'status' => 'ACCEPTED',
            'redirectUrl' => 'https://api.sandbox.pawapay.io/payment-page/xyz',
        ], 200),
    ]);

    $user = User::factory()->create();
    $property = Property::factory()->create();

    $this->actingAs($user)
        ->post(route('my-visit-passes.store'), [
            'property_id' => $property->id,
            'holder_name' => 'Jean Dupont',
            'phone' => '+242061234567',
        ])
        ->assertRedirect('https://api.sandbox.pawapay.io/payment-page/xyz');

    $visitPass = VisitPass::where('user_id', $user->id)->first();

    expect($visitPass)->not->toBeNull()
        ->and($visitPass->holder_name)->toBe('Jean Dupont')
        ->and($visitPass->payment_status)->toBe('pending')
        ->and($visitPass->transaction_id)->not->toBeNull();

    $transaction = Transaction::where('user_id', $user->id)->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->visit_pass_id)->toBe($visitPass->id)
        ->and($transaction->deposit_id)->not->toBeNull()
        ->and($transaction->amount)->toBe(5000);

    // Le depositId persisté est bien celui envoyé à pawaPay (clé d'idempotence),
    // et aucun callbackUrl n'est transmis car non supporté par l'API paymentpage.
    Http::assertSent(fn ($request) => str_contains($request->url(), '/v2/paymentpage')
        && $request['depositId'] === $transaction->deposit_id
        && ! isset($request['callbackUrl']));
});

test('store visit pass laisse la transaction en pending si l\'API pawaPay échoue', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    Http::fake([
        'api.sandbox.pawapay.io/v2/paymentpage' => Http::response('Server error', 500),
    ]);

    $user = User::factory()->create();
    $property = Property::factory()->create();

    $this->actingAs($user)
        ->post(route('my-visit-passes.store'), [
            'property_id' => $property->id,
            'holder_name' => 'Jean Dupont',
            'phone' => '+242061234567',
        ])
        ->assertRedirect();

    $transaction = Transaction::where('user_id', $user->id)->first();

    // Règle critique : en cas d'erreur HTTP, ne JAMAIS marquer failed — rester pending
    expect($transaction)->not->toBeNull()
        ->and($transaction->status)->toBe('pending')
        ->and($transaction->visit_pass_id)->not->toBeNull();
});

/*
|--------------------------------------------------------------------------
| Tests du webhook pawaPay (callback POST)
|--------------------------------------------------------------------------
*/

test('webhook accepte un callback valide et dispatch le job de traitement', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
        'services.pawapay.callback_secret' => 'my-secret',
        'services.pawapay.verify_callback_signature' => true,
    ]);

    Queue::fake();

    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => (string) Str::uuid(),
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);

    $payload = json_encode(['depositId' => $transaction->deposit_id, 'status' => 'COMPLETED']);
    $signature = hash_hmac('sha256', $payload, 'my-secret');

    $response = $this->postJson(route('transactions.webhook', $transaction), [
        'depositId' => $transaction->deposit_id,
        'status' => 'COMPLETED',
    ], [
        'X-PawaPay-Signature' => $signature,
    ]);

    $response->assertStatus(200);

    Queue::assertPushed(ProcessPawaPayCallback::class, function ($job) use ($transaction) {
        return $job->transaction->transaction_id === $transaction->transaction_id;
    });
});

test('webhook rejette un callback avec signature invalide', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
        'services.pawapay.callback_secret' => 'my-secret',
        'services.pawapay.verify_callback_signature' => true,
    ]);

    Queue::fake();

    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => (string) Str::uuid(),
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);

    $response = $this->postJson(route('transactions.webhook', $transaction), [
        'depositId' => $transaction->deposit_id,
        'status' => 'COMPLETED',
    ], [
        'X-PawaPay-Signature' => 'invalid-signature',
    ]);

    $response->assertStatus(403);
    Queue::assertNotPushed(ProcessPawaPayCallback::class);
});

test('generic_webhook accepte un callback valide et dispatch le job de traitement', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
        'services.pawapay.callback_secret' => 'my-secret',
        'services.pawapay.verify_callback_signature' => true,
    ]);

    Queue::fake();

    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => (string) Str::uuid(),
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);

    $payload = json_encode(['depositId' => $transaction->deposit_id, 'status' => 'COMPLETED']);
    $signature = hash_hmac('sha256', $payload, 'my-secret');

    $response = $this->postJson(route('transactions.generic_webhook'), [
        'depositId' => $transaction->deposit_id,
        'status' => 'COMPLETED',
    ], [
        'X-PawaPay-Signature' => $signature,
    ]);

    $response->assertStatus(200);

    Queue::assertPushed(ProcessPawaPayCallback::class, function ($job) use ($transaction) {
        return $job->transaction->transaction_id === $transaction->transaction_id;
    });
});

test('generic_webhook rejette un callback avec signature invalide', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
        'services.pawapay.callback_secret' => 'my-secret',
        'services.pawapay.verify_callback_signature' => true,
    ]);

    Queue::fake();

    $user = User::factory()->create();
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => (string) Str::uuid(),
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);

    $response = $this->postJson(route('transactions.generic_webhook'), [
        'depositId' => $transaction->deposit_id,
        'status' => 'COMPLETED',
    ], [
        'X-PawaPay-Signature' => 'invalid-signature',
    ]);

    $response->assertStatus(403);
    Queue::assertNotPushed(ProcessPawaPayCallback::class);
});

/*
|--------------------------------------------------------------------------
| Tests du job ProcessPawaPayCallback
|--------------------------------------------------------------------------
*/

test('process_pawaPay_callback met à jour la transaction à completed', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    $user = User::factory()->create();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => $depositId,
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'COMPLETED',
            'provider' => 'MTN_MOMO_COG',
        ], 200),
    ]);

    $job = new ProcessPawaPayCallback($transaction, ['status' => 'COMPLETED']);
    $job->handle(app(PawapayService::class));

    $transaction->refresh();
    expect($transaction->status)->toBe('completed');
});

test('process_pawaPay_callback met à jour la transaction à failed', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    $user = User::factory()->create();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => $depositId,
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'FAILED',
        ], 200),
    ]);

    $job = new ProcessPawaPayCallback($transaction, ['status' => 'FAILED']);
    $job->handle(app(PawapayService::class));

    $transaction->refresh();
    expect($transaction->status)->toBe('failed');
});

test('process_pawaPay_callback laisse en pending sur NOT_FOUND', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    $user = User::factory()->create();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => $depositId,
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response('', 404),
    ]);

    $job = new ProcessPawaPayCallback($transaction, ['status' => 'PENDING']);
    $job->handle(app(PawapayService::class));

    $transaction->refresh();
    // NOT_FOUND should NOT be treated as FAILED — keep as pending
    expect($transaction->status)->toBe('pending');
});

/*
|--------------------------------------------------------------------------
| Tests de la commande de réconciliation
|--------------------------------------------------------------------------
*/

test('la commande de réconciliation vérifie les paiements bloqués', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    $user = User::factory()->create();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => $depositId,
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);
    $transaction->created_at = now()->subHour();
    $transaction->updated_at = now()->subHour();
    $transaction->save();

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'COMPLETED',
        ], 200),
    ]);

    $this->artisan('pawapay:reconcile')
        ->expectsOutput('Reconciled 1 transaction(s). 0 error(s).')
        ->assertExitCode(0);

    $transaction = Transaction::where('deposit_id', $depositId)->first();
    expect($transaction->status)->toBe('completed');
});

test('la commande de réconciliation ne marque pas en failed sur NOT_FOUND', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    $user = User::factory()->create();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => $depositId,
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);
    $transaction->created_at = now()->subHour();
    $transaction->updated_at = now()->subHour();
    $transaction->save();

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response('', 404),
    ]);

    $this->artisan('pawapay:reconcile')
        ->assertExitCode(0);

    $transaction = Transaction::where('deposit_id', $depositId)->first();
    // NOT_FOUND should NOT be treated as FAILED — leave as pending
    expect($transaction->status)->toBe('pending');
});

test('la commande de réconciliation ignore les paiements récents', function () {
    config([
        'services.pawapay.base_url' => 'https://api.sandbox.pawapay.io',
        'services.pawapay.token' => 'test-token',
    ]);

    $user = User::factory()->create();
    $depositId = (string) Str::uuid();

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'deposit_id' => $depositId,
        'status' => 'pending',
        'amount' => 5000,
        'currency' => 'XAF',
    ]);
    $transaction->created_at = now()->subMinutes(5);
    $transaction->updated_at = now()->subMinutes(5);
    $transaction->save();

    Http::fake([
        "api.sandbox.pawapay.io/v2/deposits/{$depositId}" => Http::response([
            'depositId' => $depositId,
            'status' => 'COMPLETED',
        ], 200),
    ]);

    $this->artisan('pawapay:reconcile')
        ->expectsOutput('No stuck pawaPay payments found.')
        ->assertExitCode(0);

    $transaction = Transaction::where('deposit_id', $depositId)->first();
    expect($transaction->status)->toBe('pending');
});
