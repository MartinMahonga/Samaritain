<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Parcelle;
use App\Services\ParcelleService;

class ParcelleController extends Controller
{
    public function __construct(
        protected ParcelleService $parcelleService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parcelles = Parcelle::paginate(10);

        return view('pages.admin.parcelle.index', [
            'parcelles' => $parcelles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.parcelle.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParcelleRequest $request)
    {
        $data = $request->validated();

        $images = $request->hasFile('images') ? $request->file('images') : [];

        $this->parcelleService->createParcelle($data, $images);

        return redirect()->route('admin.parcelle.index')->with('success', 'La parcelle a été créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Parcelle $parcelle)
    {
        $parcelle->load(['images', 'creator']);

        return view('pages.admin.parcelle.show', [
            'parcelle' => $parcelle,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parcelle $parcelle)
    {
        $parcelle->load('images');

        return view('pages.admin.parcelle.edit', [
            'parcelle' => $parcelle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParcelleRequest $request, Parcelle $parcelle)
    {
        $data = $request->validated();

        $images = $request->hasFile('images') ? $request->file('images') : [];

        $this->parcelleService->updateParcelle($parcelle, $data, $images);

        return redirect()->route('admin.parcelle.index')
            ->with('success', 'La parcelle a été mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parcelle $parcelle)
    {
        $this->parcelleService->deleteParcelle($parcelle);

        return redirect()->route('admin.parcelle.index')->with('success', 'La parcelle a été supprimée avec succès.');
    }
}
