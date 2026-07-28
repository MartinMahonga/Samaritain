<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreInterventionRequest;
use App\Models\Artisan;
use App\Models\Intervention;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InterventionController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Intervention::class);

        $propertyIds = Property::where('created_by', auth()->id())->pluck('id');
        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);

        $query = Intervention::whereIn('property_id', $propertyIds)
            ->with('property:id,title', 'artisan:id,name');

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

        // Aggregated stats in a single query
        $stats = Intervention::whereIn('property_id', $propertyIds)
            ->selectRaw('SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as pending_count', ['pending', 'in_progress'])
            ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN cost ELSE 0 END), 0) as total_cost', ['completed'])
            ->first();

        $pending = $stats->pending_count ?? 0;
        $totalCost = $stats->total_cost ?? 0;

        return view('pages.owner.interventions.index', compact(
            'interventions', 'properties', 'pending', 'totalCost'
        ));
    }

    public function create()
    {
        Gate::authorize('create', Intervention::class);

        $properties = Property::where('created_by', auth()->id())->get(['id', 'title']);
        $artisans = Artisan::where('verified', true)->where('is_active', true)->get(['id', 'name', 'specialty']);

        return view('pages.owner.interventions.create', compact('properties', 'artisans'));
    }

    public function store(StoreInterventionRequest $request)
    {
        Gate::authorize('create', Intervention::class);

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
        Gate::authorize('view', $intervention);

        $intervention->load('property:id,title,address', 'artisan:id,name,specialty,phone');

        return view('pages.owner.interventions.show', compact('intervention'));
    }

    public function updateStatus(Intervention $intervention, Request $request)
    {
        Gate::authorize('update', $intervention);

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
