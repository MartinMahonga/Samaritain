<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreInterventionRequest;
use App\Models\Artisan;
use App\Models\Intervention;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InterventionController extends Controller
{
    public function index(Request $request)
    {
        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');
        $properties = Property::where('created_by', auth()->id())->get();

        $query = Intervention::whereIn('property_id', $propertyIds)->with('property', 'artisan');

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $interventions = $query->latest()->paginate(15)->withQueryString();

        $pending = Intervention::whereIn('property_id', $propertyIds)->whereIn('status', ['pending', 'in_progress'])->count();
        $totalCost = Intervention::whereIn('property_id', $propertyIds)->where('status', 'completed')->sum('cost');

        return view('pages.owner.interventions.index', compact(
            'interventions', 'properties', 'pending', 'totalCost'
        ));
    }

    public function create()
    {
        $properties = Property::where('created_by', auth()->id())->get();
        $artisans = Artisan::where('verified', true)->where('is_active', true)->get();
        return view('pages.owner.interventions.create', compact('properties', 'artisans'));
    }

    public function store(StoreInterventionRequest $request)
    {
        $data = $request->validated();
        $data['is_renovation'] = (bool) ($data['is_renovation'] ?? false);

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('documents/interventions', 'public');
            }
        }
        $data['photos'] = $photos ?: null;
        unset($data['photos']);

        $intervention = Intervention::create($data);

        if (! empty($photos)) {
            $intervention->update(['photos' => $photos]);
        }

        return redirect()->route('owner.interventions.show', $intervention)
            ->with('success', 'Intervention enregistrée avec succès.');
    }

    public function show(Intervention $intervention)
    {
        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');

        if (! $propertyIds->contains($intervention->property_id)) {
            abort(403);
        }

        $intervention->load('property', 'artisan');

        return view('pages.owner.interventions.show', compact('intervention'));
    }

    public function updateStatus(Intervention $intervention, Request $request)
    {
        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');

        if (! $propertyIds->contains($intervention->property_id)) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,approved,in_progress,completed,cancelled',
            'cost' => 'nullable|integer|min:0',
        ]);

        $data = ['status' => $request->status];
        if ($request->filled('cost')) {
            $data['cost'] = $request->cost;
        }

        $intervention->update($data);

        return redirect()->back()->with('success', 'Statut de l\'intervention mis à jour.');
    }
}
