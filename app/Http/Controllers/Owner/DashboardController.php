<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Intervention;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\RentPayment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Properties statistics
        $properties = Property::where('created_by', $userId)->get();
        $totalProperties = $properties->count();
        $occupiedPropertiesCount = Contract::whereIn('property_id', $properties->pluck('id'))
            ->where('status', 'active')
            ->count();
        $occupancyRate = $totalProperties > 0 ? round(($occupiedPropertiesCount / $totalProperties) * 100) : 0;

        // 2. Active contracts
        $activeContracts = Contract::whereIn('property_id', $properties->pluck('id'))
            ->where('status', 'active')
            ->with('property')
            ->get();
        $activeContractsCount = $activeContracts->count();

        // 3. Financial stats for current month
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $rentsThisMonth = RentPayment::whereIn('contract_id', Contract::whereIn('property_id', $properties->pluck('id'))->pluck('id'))
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get();

        $rentExpectedThisMonth = $rentsThisMonth->sum('amount_due');
        $rentCollectedThisMonth = $rentsThisMonth->sum('amount_paid');
        $rentPendingThisMonth = $rentExpectedThisMonth - $rentCollectedThisMonth;

        // 4. Maintenance / Interventions
        $interventionsQuery = Intervention::whereIn('property_id', $properties->pluck('id'));
        $totalInterventions = (clone $interventionsQuery)->count();
        $pendingInterventions = (clone $interventionsQuery)->whereIn('status', ['pending', 'in_progress'])->count();
        $recentInterventions = (clone $interventionsQuery)->with('property', 'artisan')->latest()->take(5)->get();

        // 5. Recent documents
        $recentDocuments = Document::where('created_by', $userId)
            ->with('property')
            ->latest()
            ->take(5)
            ->get();

        // 6. Invoices stats (pending amount)
        $unpaidInvoicesSum = Invoice::whereIn('property_id', $properties->pluck('id'))
            ->where('status', 'unpaid')
            ->sum('amount');

        return view('pages.owner.dashboard', compact(
            'totalProperties',
            'occupancyRate',
            'activeContractsCount',
            'rentExpectedThisMonth',
            'rentCollectedThisMonth',
            'rentPendingThisMonth',
            'pendingInterventions',
            'totalInterventions',
            'recentInterventions',
            'recentDocuments',
            'unpaidInvoicesSum'
        ));
    }
}
