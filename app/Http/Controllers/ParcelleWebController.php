<?php

namespace App\Http\Controllers;

class ParcelleWebController extends Controller
{
    // Page liste des parcelles
    public function index()
    {
        return view('parcelles.index');
    }

    // Page détail d'une parcelle
    public function show($id)
    {
        return view('parcelles.show', compact('id'));
    }

    // Page création d'une parcelle
    public function create()
    {
        return view('parcelles.create');
    }

    public function store()
    {
        // Le formulaire envoie directement à l'API via Alpine.js
        // Cette route existe juste pour éviter l'erreur
        return redirect()->route('parcelles.index');
    }

    public function edit($id)
    {
        return view('parcelles.edit', compact('id'));
    }
}
