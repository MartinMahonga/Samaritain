<?php

namespace App\Http\Controllers;

use App\Models\Parcelle;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the user's favorites.
     */
    public function index(): View
    {
        $user = auth()->user();

        $favoritesProperties = $user->favorites()
            ->with('images')
            ->latest()
            ->paginate(12, ['*'], 'properties_page');

        $favoritesParcels = $user->favoritesParcels()
            ->with('images')
            ->latest()
            ->paginate(12, ['*'], 'parcels_page');

        return view('pages.favorite', [
            'favoritesProperties' => $favoritesProperties,
            'favoritesParcels' => $favoritesParcels,
            // Keep $properties for backward compatibility with any other code
            'properties' => $favoritesProperties,
        ]);
    }

    /**
     * Toggle favorite status for a property.
     */
    public function toggleProperty(Property $property): JsonResponse
    {
        $user = auth()->user();

        $exists = $user->favorites()
            ->where('property_id', $property->id)
            ->exists();

        if ($exists) {
            $user->favorites()->detach($property->id);
        } else {
            $user->favorites()->attach($property->id);
        }

        return response()->json([
            'favorited' => ! $exists,
        ]);
    }

    /**
     * Legacy toggle method for property.
     */
    public function toggle(Property $property): JsonResponse
    {
        return $this->toggleProperty($property);
    }

    /**
     * Toggle favorite status for a parcel.
     */
    public function toggleParcel(Parcelle $parcel): JsonResponse
    {
        $user = auth()->user();

        $exists = $user->favoritesParcels()
            ->where('parcel_id', $parcel->id)
            ->exists();

        if ($exists) {
            $user->favoritesParcels()->detach($parcel->id);
        } else {
            $user->favoritesParcels()->attach($parcel->id);
        }

        return response()->json([
            'favorited' => ! $exists,
        ]);
    }

    /**
     * Remove the specified property from favorites.
     */
    public function destroyProperty(Property $property): RedirectResponse
    {
        auth()->user()->favorites()->detach($property->id);

        return back()->with('success', 'Propriété retirée des favoris.');
    }

    /**
     * Remove the specified parcel from favorites.
     */
    public function destroyParcel(Parcelle $parcel): RedirectResponse
    {
        auth()->user()->favoritesParcels()->detach($parcel->id);

        return back()->with('success', 'Parcelle retirée des favoris.');
    }
}
