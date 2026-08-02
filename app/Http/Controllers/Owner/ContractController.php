<?php

namespace App\Http\Controllers\Owner;

use App\Enums\Owner\ContractStatus;
use App\Events\ContractCancelled;
use App\Events\ContractFullySigned;
use App\Events\ContractPendingSignature;
use App\Events\ContractSigned;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreContractRequest;
use App\Models\Contract;
use App\Models\ContractSignature;
use App\Models\Conversation;
use App\Models\Document;
use App\Models\Property;
use App\Models\RentPayment;
use App\Models\User;
use App\Notifications\ContractCancelledNotification;
use App\Notifications\ContractCompletedNotification;
use App\Notifications\ContractSignedNotification;
use App\Notifications\ContractSigningRequestNotification;
use App\Services\ContractSignatureService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Contract::class);

        $contracts = Contract::where('created_by', auth()->id())
            ->with('property:id,title')
            ->latest()
            ->paginate(15);

        return view('pages.owner.contracts.index', compact('contracts'));
    }

    public function create()
    {
        Gate::authorize('create', Contract::class);

        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);

        return view('pages.owner.contracts.create', compact('properties'));
    }

    public function store(StoreContractRequest $request, ContractSignatureService $signatureService)
    {
        Gate::authorize('create', Contract::class);

        $data = $request->validated();
        $data['created_by'] = auth()->id();

        if ($request->filled('signature')) {
            $data['status'] = ContractStatus::PENDING_OWNER_SIGNATURE->value;
        }

        $contract = Contract::create($data);

        // Pre-generate 12 months of rent payments automatically
        $this->generateRentSchedule($contract);

        // Handle owner signature if provided
        if ($request->filled('signature')) {
            $signatureService->createSignature(
                $contract,
                auth()->user(),
                'owner',
                $request->input('signature')
            );
        }

        // Notify tenant that a contract is waiting — sent systematically on creation
        $this->notifyTenantOfContractCreation($contract);

        $message = $request->filled('signature')
            ? 'Contrat créé et signé avec succès. Le locataire a été notifié pour signer.'
            : 'Contrat créé avec succès et échéancier généré pour 12 mois. Le locataire a été notifié.';

        return redirect()->route('owner.contracts.show', $contract)
            ->with('success', $message);
    }

    /**
     * Notify the tenant that a contract has been created and is awaiting signature.
     * Sends a single email per contract creation, regardless of whether the
     * tenant already has an account or not.
     */
    protected function notifyTenantOfContractCreation(Contract $contract): void
    {
        if (empty($contract->tenant_email)) {
            return;
        }

        $tenant = User::where('email', $contract->tenant_email)->first();

        if ($tenant) {
            // Tenant has an account: database + mail notification
            $tenant->notify(new ContractSigningRequestNotification($contract, 'tenant'));
            event(new ContractPendingSignature($contract, $tenant, 'tenant'));
        } else {
            // Tenant doesn't have an account yet: send mail-only notification
            Notification::route('mail', $contract->tenant_email)
                ->notify(new ContractSigningRequestNotification($contract, 'tenant'));
        }
    }

    public function show(Contract $contract)
    {
        Gate::authorize('view', $contract);

        $contract->load('property:id,title,address,city_id', 'rentPayments');

        return view('pages.owner.contracts.show', compact('contract'));
    }

    public function generateRents(Contract $contract)
    {
        Gate::authorize('update', $contract);

        $this->generateRentSchedule($contract);

        return redirect()->back()->with('success', 'Échéancier de loyer régénéré.');
    }

    public function togglePaid(RentPayment $rentPayment)
    {
        $contract = $rentPayment->contract;
        Gate::authorize('update', $contract);

        if ($rentPayment->status === 'paid') {
            // Revert to unpaid
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

            return redirect()->back()->with('success', 'Loyer marqué comme impayé.');
        } else {
            // Mark as paid
            $rentPayment->update([
                'status' => 'paid',
                'amount_paid' => $rentPayment->amount_due,
                'paid_at' => now(),
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
                'status' => 'unpaid',
            ]);
        }
    }

    public function downloadPdf(Contract $contract)
    {
        Gate::authorize('view', $contract);

        $contract->load('property.city', 'signatures.user');
        $property = $contract->property;

        $pdf = Pdf::loadView('pages.owner.pdf.lease-contract', compact('contract', 'property'));
        $fileName = 'contrat_bail_'.$contract->id.'_'.$contract->tenant_name.'.pdf';

        return response()->streamDownload(fn () => print ($pdf->output()), $fileName);
    }

    public function sign(Request $request, Contract $contract, ContractSignatureService $signatureService)
    {
        Gate::authorize('view', $contract);

        $request->validate([
            'signature' => ['required', 'string'],
        ]);

        $signature = $signatureService->createSignature(
            $contract,
            auth()->user(),
            'owner',
            $request->input('signature')
        );

        // Check if contract is now fully signed
        $contract->refresh();
        if ($contract->isFullySigned()) {
            event(new ContractFullySigned($contract));
            $contract->creator->notify(new ContractSignedNotification($contract, 'owner'));

            $tenant = User::where('email', $contract->tenant_email)->first();
            if ($tenant) {
                $tenant->notify(new ContractCompletedNotification($contract));
            }

            // Generate final signed PDF
            $this->generateSignedPdf($contract);
        } else {
            event(new ContractSigned($contract, auth()->user(), 'owner'));
            $contract->creator->notify(new ContractSignedNotification($contract, 'owner'));

            // Notify tenant to sign
            $tenant = User::where('email', $contract->tenant_email)->first();
            if ($tenant) {
                $tenant->notify(new ContractSigningRequestNotification($contract, 'tenant'));
                event(new ContractPendingSignature($contract, $tenant, 'tenant'));
            }
        }

        return redirect()->route('owner.contracts.show', $contract)
            ->with('success', 'Contrat signé avec succès.');
    }

    /**
     * Cancel a contract (soft action — keeps data, changes status).
     */
    public function cancel(Contract $contract)
    {
        Gate::authorize('cancel', $contract);

        $contract->update([
            'status' => ContractStatus::CANCELLED->value,
            'cancelled_at' => now(),
        ]);

        event(new ContractCancelled($contract));

        // Notify tenant if they have an account
        $tenant = User::where('email', $contract->tenant_email)->first();
        if ($tenant) {
            $tenant->notify(new ContractCancelledNotification($contract));
        } else {
            Notification::route('mail', $contract->tenant_email)
                ->notify(new ContractCancelledNotification($contract));
        }

        return redirect()->route('owner.contracts.show', $contract)
            ->with('success', 'Contrat annulé avec succès.');
    }

    /**
     * Permanently delete a contract and its related files.
     * Only allowed for draft, cancelled, or rejected contracts.
     */
    public function destroy(Contract $contract)
    {
        Gate::authorize('delete', $contract);

        // Delete associated files from storage
        $this->deleteContractFiles($contract);

        // Delete related conversation (if any)
        $contract->conversation()->delete();

        // Delete related documents (receipts, signed PDFs)
        Document::where('documentable_id', $contract->id)
            ->where('documentable_type', Contract::class)
            ->delete();

        // Rent payments and signatures cascade on delete via foreign keys
        $contract->delete();

        return redirect()->route('owner.contracts.index')
            ->with('success', 'Contrat supprimé définitivement.');
    }

    /**
     * Delete all files associated with a contract from storage.
     */
    protected function deleteContractFiles(Contract $contract): void
    {
        // Delete signature images
        $contract->signatures->each(function (ContractSignature $signature) {
            Storage::delete($signature->signature_image);
        });

        // Delete contract PDF documents
        $documents = Document::where('documentable_id', $contract->id)
            ->where('documentable_type', Contract::class)
            ->get();

        $documents->each(function (Document $document) {
            Storage::delete($document->file_path);
        });

        // Delete receipt PDFs for this contract's rent payments
        $receiptDocuments = Document::where('documentable_type', RentPayment::class)
            ->whereIn('documentable_id', $contract->rentPayments()->pluck('id'))
            ->get();

        $receiptDocuments->each(function (Document $document) {
            Storage::delete($document->file_path);
        });
    }

    protected function generateSignedPdf(Contract $contract): void
    {
        $contract->load('property.city', 'signatures.user');
        $property = $contract->property;

        $pdf = Pdf::loadView('pages.owner.pdf.lease-contract', compact('contract', 'property'));

        $folder = 'documents/contracts';
        $fileName = 'contrat_bail_'.$contract->id.'_v'.$contract->contract_version.'_'.time().'.pdf';
        $fullPath = $folder.'/'.$fileName;

        Storage::put($fullPath, $pdf->output());

        Document::create([
            'property_id' => $contract->property_id,
            'name' => 'Contrat de bail - '.$contract->tenant_name.' (signé)',
            'category' => 'lease_contract',
            'file_path' => $fullPath,
            'file_size' => Storage::size($fullPath),
            'documentable_id' => $contract->id,
            'documentable_type' => Contract::class,
            'created_by' => $contract->created_by,
        ]);
    }

    protected function generateReceiptPdf(RentPayment $rentPayment)
    {
        $contract = $rentPayment->contract;
        $property = $contract->property;

        $pdf = Pdf::loadView('pages.owner.pdf.receipt', compact('rentPayment', 'contract', 'property'));

        $folder = 'documents/receipts';
        $fileName = 'recu_'.$rentPayment->id.'_'.time().'.pdf';
        $fullPath = $folder.'/'.$fileName;

        Storage::put($fullPath, $pdf->output());

        // Register in documents table
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
