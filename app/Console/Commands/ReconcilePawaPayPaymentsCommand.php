<?php

namespace App\Console\Commands;

use App\Events\PaymentCompleted;
use App\Events\PaymentFailed;
use App\Models\Transaction;
use App\Services\PawapayService;
use App\Services\RentPaymentService;
use App\Services\VisitPassService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Reconcile payments stuck in PENDING or PROCESSING.
 *
 * pawaPay callbacks can be missed (network issues, payer closing the browser
 * before redirect, etc.). This command re-checks any transaction still in
 * PENDING/PROCESSING for more than a configurable threshold (default 15 min)
 * via the pawaPay status-check endpoint.
 *
 * Crucially: a PENDING or PROCESSING status is NOT the same as FAILED.
 * Only NOT_FOUND from the status-check call should be treated as "never created".
 */
class ReconcilePawaPayPaymentsCommand extends Command
{
    protected $signature = 'pawapay:reconcile
                           {--threshold=15 : Minutes before considering a payment stuck for reconciliation}';

    protected $description = 'Reconcile pawaPay payments stuck in PENDING or PROCESSING via the status-check endpoint';

    public function handle(PawapayService $pawapayService): int
    {
        $thresholdMinutes = (int) $this->option('threshold');

        $stuckTransactions = Transaction::query()
            ->whereIn('status', ['pending', 'accepted', 'processing'])
            ->where(function ($q) {
                $q->whereNotNull('deposit_id')
                    ->orWhereNotNull('payout_id');
            })
            ->where('updated_at', '<', now()->subMinutes($thresholdMinutes))
            ->orderBy('updated_at')
            ->get();

        if ($stuckTransactions->isEmpty()) {
            $this->info('No stuck pawaPay payments found.');

            return self::SUCCESS;
        }

        $reconciled = 0;
        $errors = 0;

        foreach ($stuckTransactions as $transaction) {
            try {
                // Choose the right status-check endpoint based on the transaction type.
                if ($transaction->type === 'payout' && $transaction->payout_id) {
                    $statusResponse = $pawapayService->getPayoutStatus($transaction->payout_id);
                } else {
                    $statusResponse = $pawapayService->getDepositStatus($transaction->deposit_id);
                }
            } catch (\Throwable $e) {
                Log::error('pawaPay reconciliation failed for transaction', [
                    'transaction_id' => $transaction->transaction_id,
                    'deposit_id' => $transaction->deposit_id,
                    'error' => $e->getMessage(),
                ]);
                $errors++;

                continue;
            }

            $pawaPayStatus = strtoupper($statusResponse['status'] ?? 'UNKNOWN');
            $transaction->update(['raw_response' => $statusResponse]);

            match ($pawaPayStatus) {
                'COMPLETED' => $this->reconcileAsCompleted($transaction),
                'FAILED', 'REJECTED' => $this->reconcileAsFailed($transaction),
                'ACCEPTED', 'PROCESSING', 'PENDING' => $this->reconcileAsStillPending($transaction),
                'NOT_FOUND' => $this->reconcileAsNotFound($transaction),
                default => Log::warning('pawaPay reconciliation encountered unknown status', [
                    'transaction_id' => $transaction->transaction_id,
                    'deposit_id' => $transaction->deposit_id,
                    'status' => $pawaPayStatus,
                ]),
            };

            $reconciled++;
        }

        $this->info("Reconciled {$reconciled} transaction(s). {$errors} error(s).");

        return self::SUCCESS;
    }

    /**
     * Mark a transaction as completed during reconciliation.
     */
    protected function reconcileAsCompleted(Transaction $transaction): void
    {
        $transaction->update(['status' => 'completed']);

        if ($transaction->visit_pass_id && $transaction->visitPass) {
            app(VisitPassService::class)
                ->handleSuccessfulPayment($transaction->visitPass);
        }

        if ($transaction->rent_payment_id && $transaction->rentPayment) {
            app(RentPaymentService::class)
                ->handleSuccessfulPayment($transaction->rentPayment);
        }

        event(new PaymentCompleted($transaction));
    }

    /**
     * Mark a transaction as failed during reconciliation.
     */
    protected function reconcileAsFailed(Transaction $transaction): void
    {
        $transaction->update(['status' => 'failed']);

        if ($transaction->visit_pass_id && $transaction->visitPass) {
            app(VisitPassService::class)
                ->handleFailedPayment($transaction->visitPass);
        }

        if ($transaction->rent_payment_id && $transaction->rentPayment) {
            app(RentPaymentService::class)
                ->handleFailedPayment($transaction->rentPayment);
        }

        event(new PaymentFailed($transaction));
    }

    /**
     * Keep the transaction as pending during reconciliation.
     */
    protected function reconcileAsStillPending(Transaction $transaction): void
    {
        $transaction->update(['status' => 'pending']);
    }

    /**
     * Handle NOT_FOUND — the deposit/payout was never created, do not mark as failed.
     */
    protected function reconcileAsNotFound(Transaction $transaction): void
    {
        Log::warning('pawaPay reconciliation: NOT_FOUND — leaving as pending', [
            'transaction_id' => $transaction->transaction_id,
            'type' => $transaction->type,
            'deposit_id' => $transaction->deposit_id,
            'payout_id' => $transaction->payout_id,
        ]);

        $transaction->update(['status' => 'pending']);
    }
}
