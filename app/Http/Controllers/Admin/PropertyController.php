<?php

namespace App\Http\Controllers\Admin;

use App\Actions\UploadImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyFormRequest;
use App\Models\Amenity;
use App\Models\Arrondissement;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::paginate(10);

        return view('pages.admin.property.index', [
            'properties' => $properties,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $property->load(['amenities', 'images', 'category', 'city', 'arrondissement', 'creator']);

        return view('pages.admin.property.show', [
            'property' => $property,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.property.create', [
            'categories' => Category::select(['id', 'name'])->get(),
            'cities' => City::select(['id', 'name'])->get(),
            'amenities' => Amenity::select(['id', 'name'])->get(),
            'arrondissements' => Arrondissement::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PropertyFormRequest $request, UploadImage $storeImage)
    {
        $data = $request->validated();

        $property = Property::create([
            ...$data,
            'created_by' => Auth::id(),
        ]);

        $property->amenities()->sync($request->validated('amenities'));

        if ($request->hasFile('images')) {
            $storeImage->handle($property, $request->file('images'));
        }

        return redirect()->route('admin.property.index')->with('success', 'Le bien a été créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $property->load(['amenities', 'images']);

        return view('pages.admin.property.edit', [
            'property' => $property,
            'categories' => Category::select(['id', 'name'])->get(),
            'cities' => City::select(['id', 'name'])->get(),
            'amenities' => Amenity::select(['id', 'name'])->get(),
            'arrondissements' => Arrondissement::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PropertyFormRequest $request, Property $property, UploadImage $storeImage)
    {
        $property->update($request->validated());
        $property->amenities()->sync($request->validated('amenities'));

        // Supprimer uniquement les images non conservées
        $keptIds = $request->input('kept_images', []);

        $property->images()
            ->whereNotIn('id', $keptIds)
            ->get()
            ->each(function ($image) {
                Storage::delete($image->getRawOriginal('image_url'));
                $image->delete();
            });

        if ($request->hasFile('images')) {
            $storeImage->handle($property, $request->file('images'));
        }

        return redirect()->route('admin.property.index')
            ->with('success', 'Le bien a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->images()
            ->get()
            ->each(function ($image) {
                Storage::delete($image->getRawOriginal('image_url'));
                $image->delete();
            });

        $property->delete($property->id);

        return redirect()->route('admin.property.index')->with('success', 'Le bien a été supprimé avec succès.');
    }

    /**
     * Vérifier une propriété (is_verify = true)
     */
    public function verify(Property $property)
    {
        $property->update([
            'is_verify' => true,
        ]);

        return redirect()->route('admin.property.index')->with('success', 'Le bien a été vérifié avec succès.');
    }

    /**
     * Désactiver une propriété (is_active = false)
     */
    public function disable(Property $property)
    {
        $property->update([
            'is_active' => false,
        ]);

        return redirect()->route('admin.property.index')->with('success', 'Le bien a été désactivé avec succès.');
    }

    /**
     * Activer une propriété (is_active = true)
     */
    public function enable(Property $property)
    {
        $property->update([
            'is_active' => true,
        ]);

        return redirect()->route('admin.property.index')->with('success', 'Le bien a été activé avec succès.');
    }

    /**
     * Annuler la vérification d'une propriété (is_verify = false)
     */
    public function unverify(Property $property)
    {
        $property->update([
            'is_verify' => false,
        ]);

        return redirect()->route('admin.property.index')->with('success', 'La vérification du bien a été annulée.');
    }

    /**
     * Basculement rapide du statut is_active via AJAX
     */
    public function toggleActive(Property $property)
    {
        $property->update([
            'is_active' => ! $property->is_active,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $property->is_active,
                'message' => 'Le statut a été modifié avec succès.',
            ]);
        }

        return redirect()->route('admin.property.index')->with('success', 'Le statut du bien a été modifié.');
    }

    /**
     * Basculement rapide de la vérification via AJAX
     */
    public function toggleVerify(Property $property)
    {
        $property->update([
            'is_verify' => ! $property->is_verify,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_verify' => $property->is_verify,
                'message' => 'La vérification a été modifiée avec succès.',
            ]);
        }

        return redirect()->route('admin.property.index')->with('success', 'Le statut de vérification a été modifié.');
    }
}
