<?php

namespace App\Jobs;

use App\Events\PaymentCompleted;
use App\Events\PaymentFailed;
use App\Exceptions\PawaPayException;
use App\Models\Transaction;
use App\Services\PawapayService;
use App\Services\RentPaymentService;
use App\Services\VisitPassService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Process an incoming pawaPay callback for a deposit.
 *
 * The callback handler in the controller verifies the signature and dispatches
 * this job. The job then independently verifies the deposit status via the
 * pawaPay API before updating the local Transaction record — never trusting
 * the callback payload alone.
 */
class ProcessPawaPayCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Transaction $transaction,
        public array $callbackData,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(PawapayService $pawapayService): void
    {
        $depositId = $callbackData['depositId'] ?? $this->transaction->deposit_id;

        if (! $depositId) {
            Log::error('pawaPay callback received but no depositId on transaction', [
                'transaction_id' => $this->transaction->transaction_id,
            ]);

            return;
        }

        // Independently verify the status via the pawaPay API.
        // Never trust the callback payload alone — always confirm with pawaPay.
        try {
            if ($this->transaction->type === 'payout' && $this->transaction->payout_id) {
                $statusResponse = $pawapayService->getPayoutStatus($this->transaction->payout_id);
            } else {
                $statusResponse = $pawapayService->getDepositStatus($depositId);
            }
        } catch (PawaPayException $e) {
            // Re-throw to leverage the retry queue — the callback may arrive
            // before pawaPay has updated the status internally.
            throw $e;
        }

        $pawaPayStatus = strtoupper($statusResponse['status'] ?? 'UNKNOWN');

        $this->transaction->update([
            'raw_response' => $statusResponse,
        ]);

        match ($pawaPayStatus) {
            'COMPLETED' => $this->handleCompleted(),
            'FAILED' => $this->handleFailed(),
            'REJECTED' => $this->handleFailed(),
            'ACCEPTED', 'PROCESSING', 'PENDING' => $this->handlePending(),
            'NOT_FOUND' => $this->handleNotFound(),
            default => Log::warning('pawaPay callback received unknown status', [
                'depositId' => $depositId,
                'status' => $pawaPayStatus,
            ]),
        };
    }

    /**
     * Handle a completed payment — activate the associated VisitPass.
     */
    protected function handleCompleted(): void
    {
        $this->transaction->update(['status' => 'completed']);

        if ($this->transaction->visit_pass_id) {
            $visitPass = $this->transaction->visitPass;

            if ($visitPass) {
                app(VisitPassService::class)->handleSuccessfulPayment($visitPass);
            }
        }

        if ($this->transaction->rent_payment_id) {
            $rentPayment = $this->transaction->rentPayment;

            if ($rentPayment) {
                app(RentPaymentService::class)->handleSuccessfulPayment($rentPayment);
            }
        }

        // Fire a domain event for listeners to react to
        event(new PaymentCompleted($this->transaction));
    }

    /**
     * Handle a failed/rejected payment.
     */
    protected function handleFailed(): void
    {
        $this->transaction->update(['status' => 'failed']);

        if ($this->transaction->visit_pass_id) {
            $visitPass = $this->transaction->visitPass;

            if ($visitPass) {
                app(VisitPassService::class)->handleFailedPayment($visitPass);
            }
        }

        if ($this->transaction->rent_payment_id) {
            $rentPayment = $this->transaction->rentPayment;

            if ($rentPayment) {
                app(RentPaymentService::class)->handleFailedPayment($rentPayment);
            }
        }

        event(new PaymentFailed($this->transaction));
    }

    /**
     * Handle a payment that is still in progress — keep as pending.
     */
    protected function handlePending(): void
    {
        $this->transaction->update(['status' => 'pending']);
    }

    /**
     * Handle NOT_FOUND — the deposit was never created.
     * Do NOT mark as failed; leave as pending for reconciliation to handle.
     */
    protected function handleNotFound(): void
    {
        Log::warning('pawaPay callback received NOT_FOUND status — deposit never created', [
            'transaction_id' => $this->transaction->transaction_id,
            'deposit_id' => $this->transaction->deposit_id,
        ]);

        $this->transaction->update(['status' => 'pending']);
    }
}
