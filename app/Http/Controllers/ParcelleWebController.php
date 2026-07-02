<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Parcelle;
use App\Models\ParcelleImage;
use App\Services\ParcelleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ParcelleWebController extends Controller
{
    public function __construct(protected ParcelleService $parcelleService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'ville',
            'quartier',
            'statut',
            'viabilisee',
            'prix_min',
            'prix_max',
            'superficie_min',
        ]);

        $parcelles = $this->parcelleService->getParcelles($filters, (int) $request->input('per_page', 12));

        return view('parcelles.index', compact('parcelles', 'filters'));
    }

    public function show(Parcelle $parcelle): View
    {
        return view('parcelles.show', compact('parcelle'));
    }

    public function create(): View
    {
        Gate::authorize('create', Parcelle::class);

        return view('parcelles.create');
    }

    public function store(StoreParcelleRequest $request): RedirectResponse
    {
        Gate::authorize('create', Parcelle::class);

        $data = $request->validated();
        $data['viabilisee'] = $request->boolean('viabilisee');

        $parcelle = $this->parcelleService->createParcelle($data, $request->file('images', []));

        return to_route('parcelles.show', $parcelle)
            ->with('success', 'La parcelle a été ajoutée avec succès.');
    }

    public function edit(Parcelle $parcelle): View
    {
        Gate::authorize('update', $parcelle);

        return view('parcelles.edit', compact('parcelle'));
    }

    public function update(UpdateParcelleRequest $request, Parcelle $parcelle): RedirectResponse
    {
        Gate::authorize('update', $parcelle);

        $data = $request->validated();

        $data['viabilisee'] = $request->boolean('viabilisee');

        $this->parcelleService->updateParcelle($parcelle, $data, $request->file('images', []));

        return to_route('parcelles.edit', $parcelle)
            ->with('success', 'La parcelle a été mise à jour avec succès.');
    }

    public function destroy(Parcelle $parcelle): RedirectResponse
    {
        Gate::authorize('delete', $parcelle);

        $this->parcelleService->deleteParcelle($parcelle);

        return to_route('parcelles.index')
            ->with('success', 'La parcelle a été supprimée avec succès.');
    }

    public function deleteImage(ParcelleImage $image): RedirectResponse
    {
        Gate::authorize('deleteImage', $image->parcelle);

        $this->parcelleService->deleteImage($image->id);

        return back()->with('success', 'L’image a été supprimée avec succès.');
    }
}
