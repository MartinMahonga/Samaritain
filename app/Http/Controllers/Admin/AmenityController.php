<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAmenityRequest;
use App\Http\Requests\Admin\UpdateAmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Amenity::withCount('properties');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $amenities = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();

        return view('pages.admin.configuration.amenity.index', [
            'amenities' => $amenities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.configuration.amenity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmenityRequest $request)
    {
        Amenity::create($request->validated());

        return redirect()->route('admin.configuration.amenity.index')
            ->with('success', 'L\'équipement a été créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Amenity $amenity)
    {
        return view('pages.admin.configuration.amenity.edit', compact('amenity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        $amenity->update($request->validated());

        return redirect()->route('admin.configuration.amenity.index')
            ->with('success', 'L\'équipement a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amenity $amenity)
    {
        $usageCount = $amenity->properties()->count();

        if ($usageCount > 0) {
            return back()->with('error', "Cet équipement est utilisé par {$usageCount} bien(s). Vous pouvez le désactiver au lieu de le supprimer.");
        }

        $amenity->delete();

        return redirect()->route('admin.configuration.amenity.index')
            ->with('success', 'L\'équipement a été supprimé avec succès.');
    }

    /**
     * Toggle the active status of the resource.
     */
    public function toggleActive(Amenity $amenity)
    {
        $amenity->update([
            'is_active' => ! $amenity->is_active,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $amenity->is_active,
                'message' => 'Le statut a été modifié avec succès.',
            ]);
        }

        return back()->with('success', 'Le statut de l\'équipement a été modifié.');
    }

    /**
     * Update the sort order of the resources.
     */
    public function updateSort(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['integer', 'exists:amenities,id'],
        ]);

        foreach ($request->items as $index => $id) {
            Amenity::where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('success', 'L\'ordre d\'affichage a été mis à jour.');
    }
}
