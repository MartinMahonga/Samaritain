<?php

namespace App\Services;

use App\Models\Document;
use App\Models\RentPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Handles the post-payment lifecycle of rent payments.
 *
 * pawaPay is asynchronous: the final COMPLETED/FAILED status arrives via a
 * callback or reconciliation. This service centralises what happens to the
 * local RentPayment once that final status is known, mirroring the existing
 * VisitPassService pattern.
 */
class RentPaymentService
{
    /**
     * Mark the rent payment as paid and generate a PDF receipt.
     *
     * Called from the callback job and the reconciliation command. Runs inside
     * a DB transaction so the payment status and receipt stay consistent.
     */
    public function handleSuccessfulPayment(RentPayment $rentPayment): void
    {
        DB::transaction(function () use ($rentPayment) {
            if ($rentPayment->isPaid()) {
                // Idempotent — a repeated callback/reconciliation must not
                // double-apply side effects.
                return;
            }

            $rentPayment->markAsPaid();
            $this->generateReceiptPdf($rentPayment);
        });
    }

    /**
     * Mark the rent payment as failed so the tenant can retry.
     */
    public function handleFailedPayment(RentPayment $rentPayment): void
    {
        $rentPayment->markAsPaymentFailed();
    }

    /**
     * Generate and store a PDF receipt for the rent payment.
     *
     * Uses the contract owner (created_by) as the document author, since this
     * runs from a queued job / command where there is no authenticated user.
     */
    protected function generateReceiptPdf(RentPayment $rentPayment): void
    {
        $contract = $rentPayment->contract;
        $property = $contract->property;

        $pdf = Pdf::loadView('pages.owner.pdf.receipt', compact('rentPayment', 'contract', 'property'));

        $folder = 'documents/receipts';
        $fileName = 'recu_'.$rentPayment->id.'_'.time().'.pdf';
        $fullPath = $folder.'/'.$fileName;

        Storage::put($fullPath, $pdf->output());

        Document::create([
            'property_id' => $property->id,
            'name' => 'Reçu de loyer - '.$rentPayment->month.'/'.$rentPayment->year.' - '.$contract->tenant_name,
            'category' => 'receipt',
            'file_path' => $fullPath,
            'file_size' => Storage::size($fullPath),
            'documentable_id' => $rentPayment->id,
            'documentable_type' => RentPayment::class,
            'created_by' => $contract->created_by,
        ]);
    }
}
