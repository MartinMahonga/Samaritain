<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');
        $properties = Property::where('created_by', auth()->id())->get();

        $query = Invoice::whereIn('property_id', $propertyIds)->with('property');

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

        $totalUnpaid = Invoice::whereIn('property_id', $propertyIds)->where('status', 'unpaid')->sum('amount');
        $totalPaid = Invoice::whereIn('property_id', $propertyIds)->where('status', 'paid')->sum('amount');

        return view('pages.owner.invoices.index', compact(
            'invoices', 'properties', 'totalUnpaid', 'totalPaid'
        ));
    }

    public function create()
    {
        $properties = Property::where('created_by', auth()->id())->get();
        return view('pages.owner.invoices.create', compact('properties'));
    }

    public function store(StoreInvoiceRequest $request)
    {
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

    public function togglePaid(Invoice $invoice)
    {
        $property = Property::find($invoice->property_id);

        if ($property->created_by !== auth()->id()) {
            abort(403);
        }

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
        $property = Property::find($invoice->property_id);

        if ($property->created_by !== auth()->id()) {
            abort(403);
        }

        if ($invoice->file_path) {
            Storage::delete($invoice->file_path);
        }

        $invoice->delete();

        return redirect()->route('owner.invoices.index')
            ->with('success', 'Facture supprimée.');
    }
}
