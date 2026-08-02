<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Invoice::class);

        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');
        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);

        $query = Invoice::whereIn('property_id', $propertyIds)->with('property:id,title');

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();

        $totals = Invoice::whereIn('property_id', $propertyIds)
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'unpaid' THEN amount ELSE 0 END), 0) as total_unpaid")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END), 0) as total_paid")
            ->first();

        $totalUnpaid = $totals->total_unpaid ?? 0;
        $totalPaid = $totals->total_paid ?? 0;

        return view('pages.owner.invoices.index', compact(
            'invoices', 'properties', 'totalUnpaid', 'totalPaid'
        ));
    }

    public function create()
    {
        Gate::authorize('create', Invoice::class);

        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);

        return view('pages.owner.invoices.create', compact('properties'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        Gate::authorize('create', Invoice::class);

        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['status'] = 'unpaid';

        if ($request->hasFile('invoice_file')) {
            $data['file_path'] = $request->file('invoice_file')->store('documents/invoices');
        }

        unset($data['invoice_file']);

        Invoice::create($data);

        return redirect()->route('owner.invoices.index')
            ->with('success', 'Facture enregistrée avec succès.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);

        $invoice->load('property.city');
        $property = $invoice->property;

        $pdf = Pdf::loadView('pages.owner.pdf.invoice', compact('invoice', 'property'));
        $fileName = 'facture_'.str_pad($invoice->id, 6, '0', STR_PAD_LEFT).'.pdf';

        return response()->streamDownload(fn () => print ($pdf->output()), $fileName);
    }

    public function togglePaid(Invoice $invoice)
    {
        Gate::authorize('update', $invoice);

        if ($invoice->status === 'paid') {
            $invoice->update(['status' => 'unpaid', 'paid_at' => null]);

            return redirect()->back()->with('success', 'Facture marquée comme impayée.');
        } else {
            $invoice->update(['status' => 'paid', 'paid_at' => now()]);

            return redirect()->back()->with('success', 'Facture marquée comme payée.');
        }
    }

    public function destroy(Invoice $invoice)
    {
        Gate::authorize('delete', $invoice);

        if ($invoice->file_path) {
            Storage::delete($invoice->file_path);
        }

        $invoice->delete();

        return redirect()->route('owner.invoices.index')
            ->with('success', 'Facture supprimée.');
    }
}
