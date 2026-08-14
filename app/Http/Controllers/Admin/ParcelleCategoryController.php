<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreParcelleCategoryRequest;
use App\Http\Requests\Admin\UpdateParcelleCategoryRequest;
use App\Models\ParcelleCategory;
use Illuminate\Http\Request;

class ParcelleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ParcelleCategory::withCount('parcelles');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();

        return view('pages.admin.configuration.parcelle-category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.configuration.parcelle-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParcelleCategoryRequest $request)
    {
        ParcelleCategory::create($request->validated());

        return redirect()->route('admin.configuration.parcelle-category.index')
            ->with('success', 'La catégorie de parcelle a été créée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParcelleCategory $category)
    {
        return view('pages.admin.configuration.parcelle-category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParcelleCategoryRequest $request, ParcelleCategory $category)
    {
        $category->update($request->validated());

        return redirect()->route('admin.configuration.parcelle-category.index')
            ->with('success', 'La catégorie de parcelle a été mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParcelleCategory $category)
    {
        $usageCount = $category->parcelles()->count();

        if ($usageCount > 0) {
            return back()->with('error', "Cette catégorie est utilisée par {$usageCount} parcelle(s). Vous pouvez la désactiver au lieu de la supprimer.");
        }

        $category->delete();

        return redirect()->route('admin.configuration.parcelle-category.index')
            ->with('success', 'La catégorie de parcelle a été supprimée avec succès.');
    }

    /**
     * Toggle the active status of the resource.
     */
    public function toggleActive(ParcelleCategory $category)
    {
        $category->update([
            'is_active' => ! $category->is_active,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $category->is_active,
                'message' => 'Le statut a été modifié avec succès.',
            ]);
        }

        return back()->with('success', 'Le statut de la catégorie a été modifié.');
    }

    /**
     * Update the sort order of the resources.
     */
    public function updateSort(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['integer', 'exists:parcelle_categories,id'],
        ]);

        foreach ($request->items as $index => $id) {
            ParcelleCategory::where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('success', 'L\'ordre d\'affichage a été mis à jour.');
    }
}
