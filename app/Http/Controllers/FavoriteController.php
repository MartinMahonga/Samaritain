<?php

namespace App\Http\Controllers;

use App\Models\Property;

class FavoriteController extends Controller
{
    public function index()
    {
        $properties = auth()->user()
            ->favorites()
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('pages.favorite', [
            'properties' => $properties,
        ]);
    }

    public function toggle(Property $property)
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
}
