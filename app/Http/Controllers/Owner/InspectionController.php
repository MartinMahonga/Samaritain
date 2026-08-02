<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreInspectionRequest;
use App\Models\Contract;
use App\Models\Inspection;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Inspection::class);

        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');
        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);

        $query = Inspection::whereIn('property_id', $propertyIds)
            ->with('property:id,title', 'contract:id,tenant_name,property_id');

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $inspections = $query->latest()->paginate(15)->withQueryString();

        return view('pages.owner.inspections.index', compact('inspections', 'properties'));
    }

    public function create()
    {
        Gate::authorize('create', Inspection::class);

        $userId = auth()->id();
        $properties = Property::where('created_by', $userId)->get(['id', 'title']);
        $propertyIds = $properties->pluck('id');
        $contracts = Contract::whereIn('property_id', $propertyIds)
            ->where('status', 'active')
            ->with('property:id,title')
            ->get(['id', 'tenant_name', 'property_id']);

        return view('pages.owner.inspections.create', compact('properties', 'contracts'));
    }

    public function store(StoreInspectionRequest $request)
    {
        Gate::authorize('create', Inspection::class);

        $data = $request->validated();

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('documents/inspections', 'public');
            }
        }

        if (! empty($photos)) {
            $data['photos'] = $photos;
        }

        unset($data['photos']);
        $inspection = Inspection::create($data);

        if (! empty($photos)) {
            $inspection->update(['photos' => $photos]);
        }

        return redirect()->route('owner.inspections.show', $inspection)
            ->with('success', 'État des lieux enregistré avec succès.');
    }

    public function show(Inspection $inspection)
    {
        Gate::authorize('view', $inspection);

        $inspection->load('property:id,title,address', 'contract:id,tenant_name,tenant_phone,tenant_email,property_id');

        return view('pages.owner.inspections.show', compact('inspection'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $property = Property::findOrFail($request->property_id);

        if ($property->created_by !== auth()->id()) {
            abort(403);
        }

        $checkIn = Inspection::where('property_id', $property->id)
            ->where('type', 'check_in')
            ->latest()
            ->first();

        $checkOut = Inspection::where('property_id', $property->id)
            ->where('type', 'check_out')
            ->latest()
            ->first();

        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);

        return view('pages.owner.inspections.compare', compact('property', 'checkIn', 'checkOut', 'properties'));
    }

    public function downloadPdf(Inspection $inspection)
    {
        Gate::authorize('view', $inspection);

        $inspection->load('property:id,title,address', 'contract:id,tenant_name');

        // Generate or regenerate PDF
        $pdf = Pdf::loadView('pages.owner.pdf.inspection', compact('inspection'));
        $fileName = 'etat_des_lieux_'.$inspection->id.'.pdf';
        $path = 'documents/inspections/'.$fileName;

        Storage::put($path, $pdf->output());

        $inspection->update(['pdf_path' => $path]);

        return Storage::download($path, $fileName);
    }
}
