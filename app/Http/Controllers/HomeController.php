<?php

namespace App\Http\Controllers;

use App\Models\Parcelle;
use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $properties = Property::where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->with('images')
            ->take(10)
            ->get();

        $parcelleCount = Parcelle::count();
        $propertyCount = Property::count();

        $property = $propertyCount + $parcelleCount;

        $parcelles = Parcelle::where('statut', 'disponible')
            ->orderBy('created_at', 'desc')
            ->with(['images', 'arrondissement'])
            ->take(10)
            ->get();

        return view('index', [
            'properties' => $properties,
            'parcelles' => $parcelles,
            'propertyCount' => $property,
        ]);
    }
}
