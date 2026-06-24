<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtisanContactRequest;
use App\Models\Artisan;

class ArtisanContactController extends Controller
{
    public function store(StoreArtisanContactRequest $request, Artisan $artisan)
    {
        $artisan->contacts()->create($request->validated());

        return back()->with('success', 'Votre message a été envoyé à l\'artisan.');
    }
}
