<?php

namespace App\Http\Controllers;

use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $properties = Property::where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->with('images')
            ->get();

        return view('index', [
            'properties' => $properties,
        ]);
    }
}
