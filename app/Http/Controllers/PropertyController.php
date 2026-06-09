<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index() {
        $properties = Property::paginate(21);

        return view('pages.property.index', [
            'properties' => $properties,
            'cities' => City::select(['id', 'name'])->get()
        ]);
    }
    
public function show(Property $property)
{
    $property->load([
        'images',
        'city',
        'category',
        'amenities',
    ]);

    $similarProperties = Property::with([
            'images',
            'city',
            'category',
        ])
        ->where('id', '!=', $property->id)
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
}
