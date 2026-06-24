<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pass;
use App\Models\PassScan;
use App\Models\Property;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques utilisateurs
        $totalUsers = User::count();
        $newThisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // Statistiques passes
        $totalPasses = Pass::count();
        $activePasses = Pass::where('status', 'actif')->count();
        $expiredPasses = Pass::where('status', 'expiré')->count();
        $totalScans = PassScan::count();

        // Statistiques biens
        $totalProperties = Property::count();
        $availableProperties = Property::where('status', 'available')->count();
        $soldProperties = Property::where('status', 'sold')->count();
        $rentedProperties = Property::where('status', 'rented')->count();

        // Valeur totale des biens
        $totalValue = Property::sum('price');

        // Taux d'activité global
        $activityRate = $totalPasses > 0 ? round(($activePasses / $totalPasses) * 100) : 0;

        // Derniers utilisateurs inscrits
        $recentUsers = User::latest()->take(5)->get();

        // Derniers passes créés
        $recentPasses = Pass::latest()->take(5)->get();

        // Derniers biens ajoutés
        $recentProperties = Property::latest()->take(5)->get();

        // Derniers scans
        $recentScans = PassScan::with(['pass', 'user'])->latest()->take(10)->get();

        // Top 3 des biens les plus chers
        $topProperties = Property::orderBy('price', 'desc')->take(3)->get();

        return view('pages.admin.index', compact(
            'totalUsers',
            'newThisWeek',
            'totalPasses',
            'activePasses',
            'expiredPasses',
            'totalScans',
            'totalProperties',
            'availableProperties',
            'soldProperties',
            'rentedProperties',
            'totalValue',
            'activityRate',
            'recentUsers',
            'recentPasses',
            'recentProperties',
            'recentScans',
            'topProperties'
        ));
    }
}
