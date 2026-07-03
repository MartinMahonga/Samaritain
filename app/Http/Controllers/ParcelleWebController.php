<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcelleRequest;
use App\Http\Requests\UpdateParcelleRequest;
use App\Models\Parcelle;
use App\Models\ParcelleImage;
use App\Services\ParcelleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ParcelleWebController extends Controller
{
    public function __construct(protected ParcelleService $parcelleService) {}

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

    public function show(Request $request, Parcelle $parcelle): View
    {
        $this->registerView($request, $parcelle);

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

    /**
     * Enregistre une vue si elle n'a pas déjà été comptée dans les dernières 24h
     * pour cet IP et cette parcelle.
     */
    protected function registerView(Request $request, Parcelle $parcelle): void
    {
        $cacheKey = 'parcelle_view_'.$parcelle->id.'_'.$request->ip();

        // Cache::add() retourne true si la clé a été ajoutée (donc n'existait pas)
        if (Cache::add($cacheKey, true, now()->addHours(24))) {
            $parcelle->incrementViews();
        }
    }

    public function deleteImage(ParcelleImage $image): RedirectResponse
    {
        Gate::authorize('deleteImage', $image->parcelle);

        $this->parcelleService->deleteImage($image->id);

        return back()->with('success', 'L’image a été supprimée avec succès.');
    }

    public function dashboard()
    {
        $parcelles = Parcelle::where('created_by', Auth::id())
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Parcelle::where('created_by', Auth::id())->count(),
            'verified' => Parcelle::where('created_by', Auth::id())->where('viabilisee', true)->count(),
            'pending' => Parcelle::where('created_by', Auth::id())->where('viabilisee', false)->count(),
            'active' => Parcelle::where('created_by', Auth::id())->where('viabilisee', true)->count(),
        ];

        return view('parcelles.dashboard', [
            'parcelles' => $parcelles,
            'stats' => $stats,
        ]);
    }
}
