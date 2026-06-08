<?php

namespace App\Http\Controllers\Admin;

use App\Actions\UploadImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyFormRequest;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Category::create(['name' => 'Appartement']);
        // Category::create(['name' => 'Maison']);
        // Category::create(['name' => 'Studio']);
        // Category::create(['name' => 'Local commercial']);

        // City::create(['name' => 'Brazzaville']);
        // City::create(['name' => 'Pointe-Noire']);

        // Amenity::create(['name' => 'WiFi']);
        // Amenity::create(['name' => 'Piscine']);
        // Amenity::create(['name' => 'Parkings']);
        // Amenity::create(['name' => 'Balcon']);
        // Amenity::create(['name' => 'Jardin']);
        // Amenity::create(['name' => 'Terrain de golfe']);


        return view('pages.admin.property.create', [
            'categories' => Category::select(['id', 'name'])->get(),
            'cities' => City::select(['id', 'name'])->get(),
            'amenities' => Amenity::select(['id', 'name'])->get(),
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
                Storage::disk('public')->delete($image->getRawOriginal('image_url'));
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
                Storage::disk('public')->delete($image->getRawOriginal('image_url'));
                $image->delete();
        });

        $property->delete($property->id);

        return redirect()->route('admin.property.index')->with('success', 'Le bien a été supprimé avec succès.');
    }
}
