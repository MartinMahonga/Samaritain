# Laravel implementation

Ready-to-adapt files for a Laravel 11/12/13 app. Adjust namespaces/table names to match the existing project rather than pasting these verbatim if conventions differ.

## 1. `.env`

```env
PAWAPAY_ENV=sandbox                 # sandbox | production
PAWAPAY_API_TOKEN=
PAWAPAY_CALLBACK_VERIFY_SIGNATURE=false   # true once signed callbacks are enabled in the Dashboard
```

## 2. `config/pawapay.php`

```php
<?php

return [
    'env' => env('PAWAPAY_ENV', 'sandbox'),

    'base_url' => env('PAWAPAY_ENV', 'sandbox') === 'production'
        ? 'https://api.pawapay.io'
        : 'https://api.sandbox.pawapay.io',

    'api_token' => env('PAWAPAY_API_TOKEN'),

    'verify_callback_signature' => env('PAWAPAY_CALLBACK_VERIFY_SIGNATURE', false),

    // Default currency/country for this app — adjust per project
    'default_currency' => env('PAWAPAY_DEFAULT_CURRENCY', 'XAF'),
    'default_country' => env('PAWAPAY_DEFAULT_COUNTRY', 'COG'),
];
```

## 3. Migration

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pawapay_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique(); // depositId / payoutId / refundId
            $table->enum('type', ['deposit', 'payout', 'refund']);
            $table->string('status')->default('PENDING'); // PENDING, ACCEPTED, COMPLETED, FAILED, REJECTED, ENQUEUED, IN_RECONCILIATION
            $table->string('provider')->nullable();       // e.g. MTN_MOMO_COG
            $table->string('phone_number')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->string('country', 3)->nullable();
            $table->uuid('related_deposit_id')->nullable(); // for refunds
            $table->string('provider_transaction_id')->nullable();
            $table->json('failure_reason')->nullable();
            $table->json('raw_response')->nullable();
            // Link to your own domain model, e.g.:
            // $table->foreignId('order_id')->nullable()->constrained();
            $table->timestamps();

            $table->index(['status', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pawapay_transactions');
    }
};
```

## 4. Service class — `app/Services/PawaPayService.php`

```php
<?php

namespace App\Services;

use App\Models\PawaPayTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Response;

class PawaPayService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('pawapay.base_url');
        $this->token = config('pawapay.api_token');
    }

    protected function client()
    {
        return Http::withToken($this->token)
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout(30);
    }

    /**
     * Validate + normalize a phone number and get the predicted provider.
     */
    public function predictProvider(string $phoneNumber): array
    {
        $response = $this->client()->post('/v2/predict-provider', [
            'phoneNumber' => $phoneNumber,
        ]);

        return $response->json();
    }

    /**
     * Initiate a deposit (collect money from a customer).
     */
    public function initiateDeposit(string $phoneNumber, string $provider, string $amount, ?string $currency = null): PawaPayTransaction
    {
        $depositId = (string) Str::uuid();
        $currency ??= config('pawapay.default_currency');

        // Persist BEFORE calling the API — this is the reconciliation anchor.
        $transaction = PawaPayTransaction::create([
            'reference' => $depositId,
            'type' => 'deposit',
            'status' => 'PENDING',
            'provider' => $provider,
            'phone_number' => $phoneNumber,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        try {
            $response = $this->client()->post('/v2/deposits', [
                'depositId' => $depositId,
                'amount' => $amount,
                'currency' => $currency,
                'payer' => [
                    'type' => 'MMO',
                    'accountDetails' => [
                        'phoneNumber' => $phoneNumber,
                        'provider' => $provider,
                    ],
                ],
            ]);

            $this->handleInitiationResponse($transaction, $response);
        } catch (\Throwable $e) {
            // Network error: do NOT mark as failed. Leave PENDING, reconciliation job will check it.
            report($e);
        }

        return $transaction->fresh();
    }

    /**
     * Initiate a payout (send money to a customer).
     */
    public function initiatePayout(string $phoneNumber, string $provider, string $amount, ?string $currency = null): PawaPayTransaction
    {
        $payoutId = (string) Str::uuid();
        $currency ??= config('pawapay.default_currency');

        $transaction = PawaPayTransaction::create([
            'reference' => $payoutId,
            'type' => 'payout',
            'status' => 'PENDING',
            'provider' => $provider,
            'phone_number' => $phoneNumber,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        try {
            $response = $this->client()->post('/v2/payouts', [
                'payoutId' => $payoutId,
                'amount' => $amount,
                'currency' => $currency,
                'recipient' => [
                    'type' => 'MMO',
                    'accountDetails' => [
                        'phoneNumber' => $phoneNumber,
                        'provider' => $provider,
                    ],
                ],
            ]);

            $this->handleInitiationResponse($transaction, $response);
        } catch (\Throwable $e) {
            report($e);
        }

        return $transaction->fresh();
    }

    /**
     * Refund a completed deposit, fully or partially.
     */
    public function initiateRefund(string $depositId, ?string $amount = null, ?string $currency = null): PawaPayTransaction
    {
        $refundId = (string) Str::uuid();
        $deposit = PawaPayTransaction::where('reference', $depositId)->where('type', 'deposit')->firstOrFail();

        $transaction = PawaPayTransaction::create([
            'reference' => $refundId,
            'type' => 'refund',
            'status' => 'PENDING',
            'related_deposit_id' => $depositId,
            'amount' => $amount ?? $deposit->amount,
            'currency' => $currency ?? $deposit->currency,
        ]);

        $payload = ['refundId' => $refundId, 'depositId' => $depositId];
        if ($amount !== null) {
            $payload['amount'] = $amount;
            $payload['currency'] = $currency ?? $deposit->currency;
        }

        try {
            $response = $this->client()->post('/v2/refunds', $payload);
            $this->handleInitiationResponse($transaction, $response);
        } catch (\Throwable $e) {
            report($e);
        }

        return $transaction->fresh();
    }

    protected function handleInitiationResponse(PawaPayTransaction $transaction, Response $response): void
    {
        $data = $response->json();

        if ($response->serverError()) {
            // UNKNOWN_ERROR case: status is genuinely unknown, leave PENDING.
            $transaction->update(['raw_response' => $data]);
            return;
        }

        $transaction->update([
            'status' => $data['status'] ?? $transaction->status,
            'raw_response' => $data,
            'failure_reason' => $data['failureReason'] ?? null,
        ]);
    }

    /**
     * Poll pawaPay for the current status of a deposit/payout/refund.
     * Only source of truth to decide FAILED after a network error (NOT_FOUND).
     */
    public function checkStatus(PawaPayTransaction $transaction): array
    {
        $endpoint = match ($transaction->type) {
            'deposit' => "/v2/deposits/{$transaction->reference}",
            'payout' => "/v2/payouts/{$transaction->reference}",
            'refund' => "/v2/refunds/{$transaction->reference}",
        };

        $response = $this->client()->get($endpoint)->json();

        if (($response['status'] ?? null) === 'NOT_FOUND') {
            $transaction->update(['status' => 'FAILED']);
        } elseif (($response['status'] ?? null) === 'FOUND') {
            $data = $response['data'];
            $transaction->update([
                'status' => $data['status'],
                'provider_transaction_id' => $data['providerTransactionId'] ?? $transaction->provider_transaction_id,
                'failure_reason' => $data['failureReason'] ?? null,
                'raw_response' => $data,
            ]);
        }

        return $response;
    }
}
```

## 5. Callback controller — `app/Http/Controllers/PawaPayCallbackController.php`

Route must be **excluded from CSRF** (`bootstrap/app.php` → `$middleware->validateCsrfTokens(except: ['pawapay/callback'])` in Laravel 11+, or `VerifyCsrfToken::$except` in older versions).

```php
<?php

namespace App\Http\Controllers;

use App\Models\PawaPayTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PawaPayCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        if (config('pawapay.verify_callback_signature')) {
            if (! $this->isSignatureValid($request)) {
                Log::warning('pawaPay callback: invalid signature', ['payload' => $request->all()]);
                return response()->json(['error' => 'invalid signature'], 401);
            }
        }

        $payload = $request->all();
        $reference = $payload['depositId'] ?? $payload['payoutId'] ?? $payload['refundId'] ?? null;

        if (! $reference) {
            return response()->json(['error' => 'missing reference'], 422);
        }

        $transaction = PawaPayTransaction::where('reference', $reference)->first();

        if (! $transaction) {
            Log::warning('pawaPay callback for unknown transaction', ['reference' => $reference]);
            return response()->json(['status' => 'ignored'], 200);
        }

        $transaction->update([
            'status' => $payload['status'],
            'provider_transaction_id' => $payload['providerTransactionId'] ?? $transaction->provider_transaction_id,
            'failure_reason' => $payload['failureReason'] ?? null,
            'raw_response' => $payload,
        ]);

        // Dispatch a queued job here for side effects (unlock order, send email, etc.)
        // Keep this controller fast — pawaPay expects a quick 200.

        return response()->json(['status' => 'ok'], 200);
    }

    /**
     * RFC-9421 signature verification. See references/signatures-testing.md for details.
     * Fetch pawaPay's public key from GET /v2/public-keys and cache it.
     */
    protected function isSignatureValid(Request $request): bool
    {
        // Implement per references/signatures-testing.md — omitted here as it depends
        // on the chosen signing library (e.g. web-bot-auth / http-message-signatures for PHP).
        return true;
    }
}
```

```php
// routes/web.php or routes/api.php
Route::post('/pawapay/callback', PawaPayCallbackController::class)->name('pawapay.callback');
```

## 6. Reconciliation job — non-negotiable for production

```php
<?php

namespace App\Console\Commands;

use App\Models\PawaPayTransaction;
use App\Services\PawaPayService;
use Illuminate\Console\Command;

class ReconcilePawaPayTransactions extends Command
{
    protected $signature = 'pawapay:reconcile';
    protected $description = 'Re-check pawaPay transactions stuck pending/processing for >15 minutes';

    public function handle(PawaPayService $pawaPay): void
    {
        PawaPayTransaction::whereIn('status', ['PENDING', 'ACCEPTED', 'PROCESSING', 'ENQUEUED'])
            ->where('created_at', '<=', now()->subMinutes(15))
            ->each(function (PawaPayTransaction $transaction) use ($pawaPay) {
                $pawaPay->checkStatus($transaction);
            });
    }
}
```

```php
// routes/console.php (Laravel 11+) or app/Console/Kernel.php
Schedule::command('pawapay:reconcile')->everyFiveMinutes();
```

## 7. Example controller usage

```php
public function store(Request $request, PawaPayService $pawaPay)
{
    $validated = $request->validate([
        'phone_number' => 'required|string',
        'amount' => 'required|numeric|min:1',
    ]);

    // Normalize + validate the phone number and get the predicted provider first
    $prediction = $pawaPay->predictProvider($validated['phone_number']);

    if (! isset($prediction['provider'])) {
        return back()->withErrors(['phone_number' => 'Numéro invalide.']);
    }

    $transaction = $pawaPay->initiateDeposit(
        phoneNumber: $prediction['phoneNumber'],
        provider: $prediction['provider'], // let the customer confirm/override this in the UI
        amount: (string) $validated['amount'],
    );

    return response()->json(['reference' => $transaction->reference, 'status' => $transaction->status]);
}
```

## Frontend note (Inertia/Vue projects)

Don't hardcode the provider list/logos in the frontend — fetch `GET /v2/active-conf?country=COG&operationType=DEPOSIT` from a small backend proxy endpoint and render provider options dynamically (with logos from the response). Never pre-select a provider by default in the dropdown.
