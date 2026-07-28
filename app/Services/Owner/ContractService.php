<?php

namespace App\Services\Owner;

use App\Models\Contract;
use App\Models\Document;
use App\Models\RentPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ContractService
{
    public function generateRentSchedule(Contract $contract): void
    {
        // Delete existing unpaid rents to avoid duplication
        RentPayment::where('contract_id', $contract->id)
            ->where('status', '!=', 'paid')
            ->delete();

        $startDate = $contract->start_date;
        $monthlyRent = $contract->monthly_rent;

        for ($i = 0; $i < 12; $i++) {
            $dueDate = $startDate->copy()->addMonths($i);

            RentPayment::create([
                'contract_id' => $contract->id,
                'month' => $dueDate->month,
                'year' => $dueDate->year,
                'amount_due' => $monthlyRent,
                'amount_paid' => 0,
                'due_date' => $dueDate,
                'status' => 'unpaid',
            ]);
        }
    }

    public function markAsPaid(RentPayment $rentPayment): void
    {
        $rentPayment->update([
            'status' => 'paid',
            'amount_paid' => $rentPayment->amount_due,
            'paid_at' => now(),
        ]);

        $this->generateReceiptPdf($rentPayment);
    }

    public function markAsUnpaid(RentPayment $rentPayment): void
    {
        $rentPayment->update([
            'status' => 'unpaid',
            'amount_paid' => 0,
            'paid_at' => null,
        ]);

        // Find and delete the generated receipt document if it exists
        $document = Document::where('documentable_id', $rentPayment->id)
            ->where('documentable_type', RentPayment::class)
            ->first();

        if ($document) {
            Storage::delete($document->file_path);
            $document->delete();
        }
    }

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
            'created_by' => auth()->id(),
        ]);
    }
}
