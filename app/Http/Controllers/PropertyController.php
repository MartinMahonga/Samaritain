<?php

namespace App\Http\Controllers;

use App\Actions\UploadImage;
use App\Http\Requests\PropertyFormRequest;
use App\Models\Amenity;
use App\Models\Arrondissement;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of the user's properties.
     */
    /**
     * Display a listing of properties for public view.
     */
    public function index(Request $request)
    {
        $query = Property::query()
            ->where('is_active', true)
            ->where('is_verify', true)
            ->with(['city', 'category', 'images', 'arrondissement']);

        // Appliquer les filtres si présents
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('arrondissement_id')) {
            $query->where('arrondissement_id', $request->arrondissement_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('surface')) {
            $query->where('surface', '>=', $request->surface);
        }

        if ($request->filled('rooms')) {
            $query->where('rooms', $request->rooms);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->keyword.'%')
                    ->orWhere('description', 'like', '%'.$request->keyword.'%')
                    ->orWhere('address', 'like', '%'.$request->keyword.'%')
                    ->orWhereHas('city', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%'.$request->keyword.'%');
                    })
                    ->orWhereHas('arrondissement', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%'.$request->keyword.'%');
                    });
            });
        }

        $properties = $query->latest()->paginate(21)->withQueryString();

        return view('pages.property.index', [
            'properties' => $properties,
            'cities' => City::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Display the specified property.
     */
    public function show(Request $request, Property $property)
    {
        Gate::authorize('view', $property);

        $this->registerView($request, $property);

        $property->load([
            'images',
            'city',
            'category',
            'amenities',
            'arrondissement',
            'creator',
        ]);

        $similarProperties = Property::with([
            'images',
            'city',
            'category',
        ])
            ->where('id', '!=', $property->id)
            ->where('is_active', true)
            ->where('is_verify', true)
            ->where(function ($query) use ($property) {
                $query->where('category_id', $property->category_id)
                    ->orWhere('city_id', $property->city_id);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('pages.property.show', [
            'property' => $property,
            'similarProperties' => $similarProperties,
        ]);
    }

    public function create()
    {
        return view('pages.property.create', [
            'categories' => Category::select(['id', 'name'])->get(),
            'cities' => City::select(['id', 'name'])->get(),
            'amenities' => Amenity::select(['id', 'name'])->get(),
            'arrondissements' => Arrondissement::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(PropertyFormRequest $request, UploadImage $storeImage)
    {
        $data = $request->validated();
        unset($data['conditions']);

        $property = Property::create([
            ...$data,
            'created_by' => Auth::id(),
            'is_verify' => false, // Par défaut, non vérifié
            'is_active' => true,   // Par défaut, actif
            'conditions_accepted_at' => now(),
        ]);

        $property->amenities()->sync($request->validated('amenities'));

        if ($request->hasFile('images')) {
            $storeImage->handle($property, $request->file('images'));
        }

        return redirect()->route('property.dashboard')
            ->with('success', 'Votre bien a été créé avec succès. Il sera visible après validation par un administrateur.');
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        Gate::authorize('update', $property);

        $property->load(['amenities', 'images']);

        return view('pages.property.edit', [
            'property' => $property,
            'categories' => Category::select(['id', 'name'])->get(),
            'cities' => City::select(['id', 'name'])->get(),
            'amenities' => Amenity::select(['id', 'name'])->get(),
            'arrondissements' => Arrondissement::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Update the specified property in storage.
     */
    public function update(PropertyFormRequest $request, Property $property, UploadImage $storeImage)
    {
        Gate::authorize('update', $property);

        $property->update($request->validated());
        $property->amenities()->sync($request->validated('amenities'));

        // Après modification, le bien redevient non vérifié (nécessite une nouvelle validation)
        $property->update(['is_verify' => false]);

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

        return redirect()->route('property.dashboard', $property)
            ->with('success', 'Votre bien a été mis à jour avec succès. Il sera de nouveau vérifié par un administrateur.');
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(Property $property)
    {
        Gate::authorize('delete', $property);

        $property->images()
            ->get()
            ->each(function ($image) {
                Storage::delete($image->getRawOriginal('image_url'));
                $image->delete();
            });

        $property->delete();

        return redirect()->route('property.index')
            ->with('success', 'Votre bien a été supprimé avec succès.');
    }

    /**
     * Display properties for a specific city (filter)
     */
    public function byCity(City $city)
    {
        $properties = Property::where('city_id', $city->id)
            ->where('is_active', true)
            ->where('is_verify', true)
            ->with(['city', 'category'])
            ->latest()
            ->paginate(21);

        return view('pages.property.index', [
            'properties' => $properties,
            'cities' => City::select(['id', 'name'])->get(),
            'selectedCity' => $city,
        ]);
    }

    /**
     * Display properties by category
     */
    public function byCategory(Category $category)
    {
        $properties = Property::where('category_id', $category->id)
            ->where('is_active', true)
            ->where('is_verify', true)
            ->with(['city', 'category'])
            ->latest()
            ->paginate(21);

        return view('pages.property.index', [
            'properties' => $properties,
            'cities' => City::select(['id', 'name'])->get(),
            'selectedCategory' => $category,
        ]);
    }

    /**
     * Search properties
     */
    public function search(Request $request)
    {
        $query = Property::query()
            ->where('is_active', true)
            ->where('is_verify', true);

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('arrondissement_id')) {
            $query->where('arrondissement_id', $request->arrondissement_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('surface')) {
            $query->where('surface', '>=', $request->surface);
        }

        if ($request->filled('rooms')) {
            $query->where('rooms', $request->rooms);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->keyword.'%')
                    ->orWhere('description', 'like', '%'.$request->keyword.'%')
                    ->orWhere('address', 'like', '%'.$request->keyword.'%')
                    ->orWhereHas('city', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%'.$request->keyword.'%');
                    })
                    ->orWhereHas('arrondissement', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%'.$request->keyword.'%');
                    });
            });
        }

        $properties = $query->with(['city', 'category', 'arrondissement', 'images'])
            ->latest()
            ->paginate(21)
            ->withQueryString();

        return view('pages.property.index', [
            'properties' => $properties,
            'cities' => City::select(['id', 'name'])->get(),
            'filters' => $request->all(),
        ]);
    }

    /**
     * Display user's dashboard with their properties
     */
    public function dashboard()
    {
        $properties = Property::where('created_by', Auth::id())
            ->with(['city', 'category'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Property::where('created_by', Auth::id())->count(),
            'verified' => Property::where('created_by', Auth::id())->where('is_verify', true)->count(),
            'pending' => Property::where('created_by', Auth::id())->where('is_verify', false)->count(),
            'active' => Property::where('created_by', Auth::id())->where('is_active', true)->count(),
        ];

        return view('pages.property.dashboard', [
            'properties' => $properties,
            'stats' => $stats,
        ]);
    }

    /**
     * Duplicate a property (for quick creation)
     */
    public function duplicate(Property $property, UploadImage $storeImage)
    {
        Gate::authorize('update', $property);

        // Créer une copie
        $newProperty = $property->replicate();
        $newProperty->title = $property->title.' (Copie)';
        $newProperty->slug = $this->generateUniqueSlug($newProperty->title);
        $newProperty->is_verify = false;
        $newProperty->created_by = Auth::id();
        $newProperty->save();

        // Copier les amenities
        $newProperty->amenities()->sync($property->amenities->pluck('id')->toArray());

        // Copier les images
        foreach ($property->images as $image) {
            $oldPath = $image->getRawOriginal('image_url');
            $newPath = 'properties/'.uniqid().'.jpg';

            // Copier le fichier
            if (Storage::exists($oldPath)) {
                Storage::copy($oldPath, $newPath);

                // Créer la nouvelle image
                $newProperty->images()->create([
                    'image_url' => $newPath,
                ]);
            }
        }

        return redirect()->route('property.edit', $newProperty)
            ->with('success', 'Bien dupliqué avec succès. Vous pouvez maintenant modifier les informations.');
    }

    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Property::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Enregistre une vue si elle n'a pas déjà été comptée dans les dernières 24h
     * pour cet IP et ce bien.
     */
    protected function registerView(Request $request, Property $property): void
    {
        $cacheKey = 'property_view_'.$property->id.'_'.$request->ip();

        // Cache::add() retourne true si la clé a été ajoutée (donc n'existait pas)
        if (Cache::add($cacheKey, true, now()->addHours(24))) {
            $property->incrementViews();
        }
    }
}
