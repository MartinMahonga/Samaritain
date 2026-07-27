<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreContractRequest;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Property;
use App\Models\RentPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function index()
    {
        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');
        $contracts = Contract::whereIn('property_id', $propertyIds)
            ->with('property')
            ->latest()
            ->paginate(15);

        return view('pages.owner.contracts.index', compact('contracts'));
    }

    public function create()
    {
        $properties = Property::where('created_by', auth()->id())->get();
        return view('pages.owner.contracts.create', compact('properties'));
    }

    public function store(StoreContractRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $contract = Contract::create($data);

        // Pre-generate 12 months of rent payments automatically
        $this->generateRentSchedule($contract);

        return redirect()->route('owner.contracts.show', $contract)
            ->with('success', 'Contrat créé avec succès et échéancier généré pour 12 mois.');
    }

    public function show(Contract $contract)
    {
        // Authorization check
        if ($contract->created_by !== auth()->id()) {
            abort(403);
        }

        $contract->load('property', 'rentPayments');
        return view('pages.owner.contracts.show', compact('contract'));
    }

    public function generateRents(Contract $contract)
    {
        if ($contract->created_by !== auth()->id()) {
            abort(403);
        }

        $this->generateRentSchedule($contract);

        return redirect()->back()->with('success', 'Échéancier de loyer régénéré.');
    }

    public function togglePaid(RentPayment $rentPayment)
    {
        $contract = $rentPayment->contract;
        if ($contract->created_by !== auth()->id()) {
            abort(403);
        }

        if ($rentPayment->status === 'paid') {
            // Revert to unpaid
            $rentPayment->update([
                'status' => 'unpaid',
                'amount_paid' => 0,
                'paid_at' => null
            ]);

            // Find and delete the generated receipt document if it exists
            $document = Document::where('documentable_id', $rentPayment->id)
                ->where('documentable_type', RentPayment::class)
                ->first();

            if ($document) {
                Storage::delete($document->file_path);
                $document->delete();
            }

            return redirect()->back()->with('success', 'Loyer marqué comme impayé.');
        } else {
            // Mark as paid
            $rentPayment->update([
                'status' => 'paid',
                'amount_paid' => $rentPayment->amount_due,
                'paid_at' => now()
            ]);

            // Automatically generate a PDF Receipt
            $this->generateReceiptPdf($rentPayment);

            return redirect()->back()->with('success', 'Loyer marqué comme payé et reçu généré.');
        }
    }

    protected function generateRentSchedule(Contract $contract)
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
                'status' => 'unpaid'
            ]);
        }
    }

    protected function generateReceiptPdf(RentPayment $rentPayment)
    {
        $contract = $rentPayment->contract;
        $property = $contract->property;

        $pdf = Pdf::loadView('pages.owner.pdf.receipt', compact('rentPayment', 'contract', 'property'));
        
        $folder = 'documents/receipts';
        $fileName = 'recu_' . $rentPayment->id . '_' . time() . '.pdf';
        $fullPath = $folder . '/' . $fileName;

        Storage::put($fullPath, $pdf->output());

        // Register in documents table
        Document::create([
            'property_id' => $property->id,
            'name' => 'Reçu de loyer - ' . $rentPayment->month . '/' . $rentPayment->year . ' - ' . $contract->tenant_name,
            'category' => 'receipt',
            'file_path' => $fullPath,
            'file_size' => Storage::size($fullPath),
            'documentable_id' => $rentPayment->id,
            'documentable_type' => RentPayment::class,
            'created_by' => auth()->id()
        ]);
    }
}
