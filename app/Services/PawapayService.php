<?php

namespace App\Services;

use App\Exceptions\PawaPayException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for interacting with the pawaPay mobile money API.
 *
 * pawaPay is an asynchronous payment gateway: you initiate a deposit/payout,
 * get an ACCEPTED/REJECTED response immediately, then the final status
 * (COMPLETED/FAILED) arrives later via a callback.
 *
 * Key rules:
 *  - depositId/payoutId must be a UUIDv4 generated and persisted BEFORE calling the API.
 *  - Never mark a payment FAILED just because the HTTP call errored or timed out.
 *    Only trust NOT_FOUND from a status-check call for that.
 *  - Amounts are strings, not floats.
 */
class PawapayService
{
    protected string $baseUrl;

    protected string $token;

    protected ?string $callbackSecret;

    protected bool $verifyCallbackSignature;

    public function __construct()
    {
        $this->baseUrl = config('services.pawapay.base_url');
        $this->token = config('services.pawapay.token');
        $this->callbackSecret = config('services.pawapay.callback_secret');
        $this->verifyCallbackSignature = (bool) config('services.pawapay.verify_callback_signature', false);
    }

    /**
     * Build a base HTTP request with authentication and JSON headers.
     */
    protected function httpClient()
    {
        return Http::withToken($this->token)
            ->acceptJson()
            ->timeout(30);
    }

    /**
     * Predict the mobile money provider for a given MSISDN.
     *
     * Always use this endpoint to normalize phone numbers and detect the
     * provider — never hand-roll phone validation.
     *
     * @param  string  $msisdn  The phone number in E.164 format.
     * @return array{predictedProvider?: string, msisdn?: string, ...}
     *
     * @throws PawaPayException
     */
    public function predictProvider(string $msisdn): array
    {
        $response = $this->httpClient()
            ->post("{$this->baseUrl}/v2/predict-provider", [
                'msisdn' => $msisdn,
            ]);

        if ($response->failed()) {
            Log::warning('pawaPay predict-provider failed', [
                'msisdn' => $msisdn,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PawaPayException(
                'Impossible de prédire le fournisseur pour ce numéro.',
                $response->status(),
                $response->body(),
            );
        }

        return $response->json();
    }

    /**
     * Retrieve active configuration (supported providers, currency, decimals, etc.).
     *
     * Check decimalsInAmount (NONE vs TWO_PLACES) before rounding/formatting amounts.
     *
     * @return array<string, mixed>
     *
     * @throws PawaPayException
     */
    public function getActiveConfiguration(): array
    {
        $response = $this->httpClient()
            ->get("{$this->baseUrl}/v2/active-configuration");

        if ($response->failed()) {
            Log::warning('pawaPay active-configuration request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PawaPayException(
                'Impossible de récupérer la configuration pawaPay.',
                $response->status(),
                $response->body(),
            );
        }

        return $response->json();
    }

    /**
     * Create a deposit (collect money from a customer).
     *
     * The depositId must be a UUIDv4 generated and persisted by your application
     * BEFORE calling this method. Reusing a depositId returns DUPLICATE_IGNORED.
     *
     * @param  string  $depositId  The UUIDv4 idempotency key.
     * @param  array  $data  The deposit payload (payer, amountDetails, provider, etc.).
     * @return array The pawaPay response containing status, provider, etc.
     *
     * @throws PawaPayException
     */
    public function createDeposit(string $depositId, array $data): array
    {
        $payload = array_merge($data, ['depositId' => $depositId]);

        $response = $this->httpClient()
            ->post("{$this->baseUrl}/v2/deposits", $payload);

        if ($response->failed()) {
            Log::warning('pawaPay deposit creation failed', [
                'depositId' => $depositId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PawaPayException(
                'Erreur lors de la création du dépôt pawaPay.',
                $response->status(),
                $response->body(),
            );
        }

        return $response->json();
    }

    /**
     * Create a hosted payment page (deposit via redirect widget).
     *
     * @param  array  $data  Payment page payload (depositId, returnUrl, amountDetails, country, etc.).
     * @return array The pawaPay response containing status and redirectUrl.
     *
     * @throws PawaPayException
     */
    public function createPaymentPage(array $data): array
    {
        // pawaPay V2 hosted payment page does not support callbackUrl in the request body.
        // Webhooks must be configured globally in the pawaPay Merchant Dashboard.
        if (array_key_exists('callbackUrl', $data)) {
            unset($data['callbackUrl']);
        }

        // pawaPay API rejects "localhost" in returnUrl. We normalize it to "127.0.0.1" for local testing.
        if (isset($data['returnUrl'])) {
            $data['returnUrl'] = str_replace('//localhost', '//127.0.0.1', $data['returnUrl']);
        }

        $response = $this->httpClient()
            ->post("{$this->baseUrl}/v2/paymentpage", $data);

        if ($response->failed()) {
            Log::warning('pawaPay payment page creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PawaPayException(
                'Erreur lors de la création de la page de paiement pawaPay.',
                $response->status(),
                $response->body(),
            );
        }

        return $response->json();
    }

    /**
     * Check the status of a deposit.
     *
     * The final status (COMPLETED/FAILED) arrives via callback. This endpoint
     * is primarily used for reconciliation of payments stuck in
     * PENDING/PROCESSING. NOT_FOUND means the deposit was never created —
     * do NOT treat it as FAILED.
     *
     * @param  string  $depositId  The UUIDv4 deposit identifier.
     * @return array The pawaPay status response.
     *
     * @throws PawaPayException Only for HTTP-level failures; NOT_FOUND is returned as a status array.
     */
    public function getDepositStatus(string $depositId): array
    {
        $response = $this->httpClient()
            ->get("{$this->baseUrl}/v2/deposits/{$depositId}");

        // NOT_FOUND is a legitimate result — the deposit may not have been created
        if ($response->status() === 404) {
            return [
                'depositId' => $depositId,
                'status' => 'NOT_FOUND',
                'reason' => 'Deposit not found',
            ];
        }

        if ($response->failed()) {
            Log::warning('pawaPay status check failed', [
                'depositId' => $depositId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PawaPayException(
                'Erreur lors de la vérification du statut du dépôt.',
                $response->status(),
                $response->body(),
            );
        }

        return $response->json();
    }

    /**
     * Verify a pawaPay callback signature (HMAC-SHA256 of the request body).
     *
     * @param  string  $payload  The raw request body.
     * @param  string  $signature  The signature header value.
     * @return bool True if the signature is valid (or if no secret is configured).
     */
    public function verifyCallbackSignature(string $payload, string $signature): bool
    {
        // Signature verification is disabled unless explicitly enabled.
        if (! $this->verifyCallbackSignature) {
            return true;
        }

        if (! $this->callbackSecret) {
            Log::warning('pawaPay signature verification is enabled but no callback secret is configured — verification skipped.');

            return true;
        }

        $expected = hash_hmac('sha256', $payload, $this->callbackSecret);

        return hash_equals($expected, $signature);
    }

    /**
     * Initiate a payout (send money to a recipient via Mobile Money).
     *
     * The payoutId must be a UUIDv4 generated and persisted by your application
     * BEFORE calling this method. Reusing a payoutId returns DUPLICATE_IGNORED.
     *
     * @param  string  $payoutId  The UUIDv4 idempotency key.
     * @param  array  $data  The payout payload (recipient, amountDetails, provider, etc.)
     * @return array The pawaPay response containing status, provider, etc.
     *
     * @throws PawaPayException
     */
    public function createPayout(string $payoutId, array $data): array
    {
        $payload = array_merge($data, ['payoutId' => $payoutId]);

        $response = $this->httpClient()
            ->post("{$this->baseUrl}/v2/payouts", $payload);

        if ($response->failed()) {
            Log::warning('pawaPay payout creation failed', [
                'payoutId' => $payoutId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PawaPayException(
                'Erreur lors de la création du paiement sortant pawaPay.',
                $response->status(),
                $response->body(),
            );
        }

        return $response->json();
    }

    /**
     * Check the status of a payout.
     *
     * Used for reconciliation of payouts stuck in PENDING/PROCESSING.
     * NOT_FOUND means the payout was never created — do NOT treat as FAILED.
     *
     * @param  string  $payoutId  The UUIDv4 payout identifier.
     * @return array The pawaPay status response.
     *
     * @throws PawaPayException Only for HTTP-level failures; NOT_FOUND is returned as a status array.
     */
    public function getPayoutStatus(string $payoutId): array
    {
        $response = $this->httpClient()
            ->get("{$this->baseUrl}/v2/payouts/{$payoutId}");

        if ($response->status() === 404) {
            return [
                'payoutId' => $payoutId,
                'status' => 'NOT_FOUND',
                'reason' => 'Payout not found',
            ];
        }

        if ($response->failed()) {
            Log::warning('pawaPay payout status check failed', [
                'payoutId' => $payoutId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PawaPayException(
                'Erreur lors de la vérification du statut du paiement sortant.',
                $response->status(),
                $response->body(),
            );
        }

        return $response->json();
    }
}
