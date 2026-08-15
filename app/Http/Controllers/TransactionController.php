<?php

namespace App\Http\Controllers;

use App\Exceptions\PawaPayException;
use App\Jobs\ProcessPawaPayCallback;
use App\Models\Transaction;
use App\Services\PawapayService;
use App\Services\RentPaymentService;
use App\Services\VisitPassService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function __construct(
        protected PawapayService $pawapay,
        protected VisitPassService $visitPassService,
    ) {}

    /**
     * Initiate a payment via the pawaPay hosted payment page.
     *
     * Flow:
     *  1. Generate a UUIDv4 (depositId) — the idempotency key and reconciliation anchor.
     *  2. Persist Transaction as PENDING with the depositId.
     *  3. Call pawaPay PaymentPage API.
     *  4. Store the raw response and redirect to the hosted page.
     *
     * Critical rule: if the HTTP call fails, do NOT mark the transaction as FAILED.
     * Leave it as PENDING — the reconciliation job will check the status later.
     */
    public function paymentPage()
    {
        $user = auth()->user();

        // 1. Generate the UUIDv4 idempotency key before any API call.
        $depositId = (string) Str::uuid();

        // 2. Persist the transaction BEFORE calling pawaPay — this is your
        //    reconciliation anchor if the network call fails or times out.
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'amount' => 5000,
            'deposit_id' => $depositId,
            'provider' => null,
            'currency' => 'XAF',
        ]);

        // 3. Call pawaPay to create the hosted payment page.
        try {
            $result = $this->pawapay->createPaymentPage([
                'depositId' => $depositId,
                'returnUrl' => route('transactions.callback', $transaction),
                'customerMessage' => 'Samaritain',
                'amountDetails' => [
                    'amount' => (string) $transaction->amount,
                    'currency' => 'XAF',
                ],
                'language' => 'FR',
                'country' => 'COG',
                'reason' => 'Paiement du pass visite',
                'metadata' => [
                    ['transactionId' => $transaction->transaction_id],
                    ['userId' => (string) $transaction->user_id],
                ],
            ]);
        } catch (PawaPayException $e) {
            // Do NOT mark as failed — leave as pending for reconciliation.
            $transaction->update([
                'raw_response' => ['error' => $e->getMessage(), 'status_code' => $e->getStatusCode()],
            ]);

            return redirect()->back()->with([
                'error' => 'Une erreur est survenue lors de la création de la page de paiement. Veuillez réessayer.',
            ]);
        }

        // 4. Store the response and redirect to the hosted payment page.
        $transaction->update([
            'status' => strtolower($result['status'] ?? 'pending'),
            'provider' => $result['provider'] ?? null,
            'raw_response' => $result,
        ]);

        return redirect($result['redirectUrl']);
    }

    /**
     * Handle the pawaPay browser redirect (returnUrl) after payment.
     *
     * The user's browser is redirected here after completing the payment on
     * pawaPay's hosted page. The actual status verification happens via
     * the server-to-server webhook callback. Here we show the user a
     * simple result page.
     */
    public function callback(Transaction $transaction)
    {
        $user = auth()->user();

        if ($transaction->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à consulter cette transaction.');
        }

        if ($transaction->visit_pass_id && $transaction->visitPass) {
            $visitPass = $transaction->visitPass;

            if ($visitPass->isPaid()) {
                return redirect()->route('my-visit-passes.show', $visitPass)
                    ->with('success', 'Paiement confirmé avec succès ! Votre pass visite est disponible.');
            }
        }

        if ($transaction->rent_payment_id) {
            return redirect()->route('tenant.payments')
                ->with('success', 'Votre paiement de loyer a bien été pris en compte.');
        }
    }

    /**
     * Handle the pawaPay server-to-server webhook callback — POST.
     *
     * Verify the signature (if configured), then dispatch a queued job that
     * independently verifies the deposit status via the pawaPay API before
     * updating the local record. Respond 200 immediately — all heavy work
     * is offloaded to the queue.
     */
    public function handleCallback(Request $request, Transaction $transaction)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-PawaPay-Signature', '');

        if (! $this->pawapay->verifyCallbackSignature($payload, $signature)) {
            \Log::warning('pawaPay callback signature verification failed', [
                'transaction_id' => $transaction->transaction_id,
                'signature' => $signature,
            ]);

            return response('Invalid signature', 403);
        }

        $callbackData = $request->validate([
            'depositId' => ['nullable', 'string'],
            'payoutId' => ['nullable', 'string'],
            'refundId' => ['nullable', 'string'],
            'event' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
        ]);

        ProcessPawaPayCallback::dispatch($transaction, $callbackData);

        return response('OK', 200);
    }

    /**
     * Handle the pawaPay server-to-server generic webhook callback — POST.
     *
     * This endpoint is called without a dynamic transaction ID. It identifies the
     * transaction in the database using the depositId, payoutId, or refundId
     * from the webhook payload.
     */
    public function handleGenericCallback(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-PawaPay-Signature', '');

        if (! $this->pawapay->verifyCallbackSignature($payload, $signature)) {
            \Log::warning('pawaPay generic callback signature verification failed', [
                'signature' => $signature,
            ]);

            return response('Invalid signature', 403);
        }

        $callbackData = $request->validate([
            'depositId' => ['nullable', 'string'],
            'payoutId' => ['nullable', 'string'],
            'refundId' => ['nullable', 'string'],
            'event' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
        ]);

        $reference = $callbackData['depositId'] ?? $callbackData['payoutId'] ?? $callbackData['refundId'] ?? null;

        if (! $reference) {
            return response('Missing reference', 400);
        }

        $transaction = Transaction::where('deposit_id', $reference)
            ->orWhere('payout_id', $reference)
            ->first();

        if (! $transaction) {
            \Log::warning('pawaPay generic callback: transaction not found', [
                'reference' => $reference,
            ]);

            return response('Transaction not found', 404);
        }

        ProcessPawaPayCallback::dispatch($transaction, $callbackData);

        return response('OK', 200);
    }

    /**
     * Manually check the status of a deposit via the pawaPay API.
     */
    public function status(Transaction $transaction)
    {
        $user = auth()->user();

        if ($transaction->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à consulter cette transaction.');
        }

        if (! $transaction->deposit_id) {
            return redirect()->back()->with('error', 'Aucun identifiant de dépôt n\'est associé à cette transaction.');
        }

        try {
            $statusResponse = $this->pawapay->getDepositStatus($transaction->deposit_id);
        } catch (PawaPayException $e) {
            return redirect()->back()->with('error', 'Impossible de vérifier le statut du paiement auprès de pawaPay.');
        }

        $pawaPayStatus = strtoupper($statusResponse['status'] ?? 'UNKNOWN');

        $transaction->update([
            'raw_response' => $statusResponse,
            'status' => $this->mapPawaPayStatus($pawaPayStatus),
        ]);

        if ($pawaPayStatus === 'COMPLETED' && $transaction->visit_pass_id) {
            $visitPass = $transaction->visitPass;
            if ($visitPass && ! $visitPass->isPaid()) {
                $this->visitPassService->handleSuccessfulPayment($visitPass);
            }
        }

        if (in_array($pawaPayStatus, ['FAILED', 'REJECTED']) && $transaction->visit_pass_id) {
            $visitPass = $transaction->visitPass;
            if ($visitPass && ! $visitPass->isPaymentFailed()) {
                $this->visitPassService->handleFailedPayment($visitPass);
            }
        }

        if ($pawaPayStatus === 'COMPLETED' && $transaction->rent_payment_id && $transaction->rentPayment) {
            app(RentPaymentService::class)->handleSuccessfulPayment($transaction->rentPayment);
        }

        if (in_array($pawaPayStatus, ['FAILED', 'REJECTED']) && $transaction->rent_payment_id && $transaction->rentPayment) {
            app(RentPaymentService::class)->handleFailedPayment($transaction->rentPayment);
        }

        return redirect()->back()->with('status', 'Statut du paiement: '.$pawaPayStatus);
    }

    /**
     * Map pawaPay statuses to local transaction statuses.
     */
    protected function mapPawaPayStatus(string $status): string
    {
        return match ($status) {
            'COMPLETED' => 'completed',
            'FAILED', 'REJECTED' => 'failed',
            'ACCEPTED' => 'accepted',
            'PROCESSING' => 'processing',
            'PENDING' => 'pending',
            default => 'pending',
        };
    }
}
