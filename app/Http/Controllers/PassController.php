<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassRequest;
use App\Models\Pass;
use App\Services\PassScanService;
use App\Services\PassService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PassController extends Controller
{
    public function __construct(
        private PassService $passService,
        private PassScanService $scanService
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Pass::class);

        $filters = $request->only(['filter', 'search']);
        $passes = $this->passService->searchPasses($filters);
        $statistics = $this->passService->getStatistics();

        return view('passes.index', compact('passes', 'statistics', 'filters'));
    }

    public function create()
    {
        Gate::authorize('create', Pass::class);

        return view('passes.create');
    }

    public function store(PassRequest $request)
    {
        Gate::authorize('create', Pass::class);

        $pass = $this->passService->createPass($request->validated());

        return redirect()->route('passes.show', $pass)
            ->with('success', 'Pass créé avec succès');
    }

    public function show(Pass $pass)
    {
        Gate::authorize('view', $pass);

        $scans = $this->scanService->getPassScansHistory($pass);

        return view('passes.show', compact('pass', 'scans'));
    }

    public function edit(Pass $pass)
    {
        Gate::authorize('update', $pass);

        return view('passes.edit', compact('pass'));
    }

    public function update(PassRequest $request, Pass $pass)
    {
        Gate::authorize('update', $pass);

        $pass = $this->passService->updatePass($pass, $request->validated());

        return redirect()->route('passes.show', $pass)
            ->with('success', 'Pass mis à jour avec succès');
    }

    public function destroy(Pass $pass)
    {
        Gate::authorize('delete', $pass);

        $this->passService->deletePass($pass);

        return redirect()->route('passes.index')
            ->with('success', 'Pass supprimé avec succès');
    }

    public function export(Pass $pass)
    {
        Gate::authorize('view', $pass);

        // Export PDF logic
        $pdf = Pdf::loadView('passes.export', compact('pass'));

        return $pdf->download('pass-'.$pass->uuid.'.pdf');
    }

    /**
     * Affiche la page des statistiques
     */
    public function statistics()
    {
        Gate::authorize('viewAny', Pass::class);

        $statistics = $this->passService->getStatistics();

        // Statistiques supplémentaires pour le dashboard
        $recentScans = $this->scanService->getRecentScans(10);
        $expiringSoon = Pass::where('expiration_date', '<=', now()->addDays(3))
            ->where('status', 'actif')
            ->count();
        $mostActivePasses = Pass::withCount('scans')
            ->orderBy('scans_count', 'desc')
            ->limit(5)
            ->get();

        return view('statistics', compact('statistics', 'recentScans', 'expiringSoon', 'mostActivePasses'));
    }
}
