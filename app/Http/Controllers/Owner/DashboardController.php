<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Intervention;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\RentPayment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Get user's property IDs once and reuse
        $propertyIds = Property::where('created_by', $userId)->pluck('id');
        $totalProperties = $propertyIds->count();

        // 1. Statistics using sub-queries for efficient DB usage
        $occupiedPropertiesCount = Contract::whereIn('property_id', $propertyIds)
            ->where('status', 'active')
            ->distinct('property_id')
            ->count('property_id');
        $occupancyRate = $totalProperties > 0 ? round(($occupiedPropertiesCount / $totalProperties) * 100) : 0;

        // 2. Active contracts count
        $activeContractsCount = Contract::whereIn('property_id', $propertyIds)
            ->where('status', 'active')
            ->count();

        // 3. Financial stats for current month - single optimized query
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $contractIds = Contract::whereIn('property_id', $propertyIds)->pluck('id');

        $rentStats = RentPayment::whereIn('contract_id', $contractIds)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->selectRaw('COALESCE(SUM(amount_due), 0) as expected, COALESCE(SUM(amount_paid), 0) as collected')
            ->first();

        $rentExpectedThisMonth = $rentStats->expected ?? 0;
        $rentCollectedThisMonth = $rentStats->collected ?? 0;
        $rentPendingThisMonth = $rentExpectedThisMonth - $rentCollectedThisMonth;

        // 4. Maintenance / Interventions - single query with aggregation
        $interventionStats = Intervention::whereIn('property_id', $propertyIds)
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as pending', ['pending', 'in_progress'])
            ->first();

        $totalInterventions = $interventionStats->total ?? 0;
        $pendingInterventions = $interventionStats->pending ?? 0;
        $recentInterventions = Intervention::whereIn('property_id', $propertyIds)
            ->with('property:id,title', 'artisan:id,name')
            ->latest()
            ->take(5)
            ->get();

        // 5. Recent documents
        $recentDocuments = Document::where('created_by', $userId)
            ->with('property:id,title')
            ->latest()
            ->take(5)
            ->get();

        // 6. Invoices stats (pending amount)
        $unpaidInvoicesSum = Invoice::whereIn('property_id', $propertyIds)
            ->where('status', 'unpaid')
            ->sum('amount');

        // 7. Monthly revenue data for charts (current year)
        $monthlyRevenue = array_fill(0, 12, 0);
        $monthlyExpenses = array_fill(0, 12, 0);

        $rentsByMonth = RentPayment::whereIn('contract_id', $contractIds)
            ->where('year', $currentYear)
            ->where('status', 'paid')
            ->selectRaw('month, COALESCE(SUM(amount_paid), 0) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        foreach ($rentsByMonth as $month => $total) {
            $monthlyRevenue[$month - 1] = (int) $total;
        }

        $expensesByMonth = Intervention::whereIn('property_id', $propertyIds)
            ->where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(cost), 0) as total')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        foreach ($expensesByMonth as $month => $total) {
            $monthlyExpenses[$month - 1] = (int) $total;
        }

        // 8. Collection rate trend (last 6 months)
        $collectionTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $m = $date->month;
            $y = $date->year;

            $due = RentPayment::whereIn('contract_id', $contractIds)
                ->where('month', $m)->where('year', $y)
                ->sum('amount_due');

            $paid = RentPayment::whereIn('contract_id', $contractIds)
                ->where('month', $m)->where('year', $y)
                ->sum('amount_paid');

            $collectionTrend[] = [
                'label' => $date->translatedFormat('M'),
                'rate' => $due > 0 ? round(($paid / $due) * 100) : 0,
            ];
        }

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
            'unpaidInvoicesSum',
            'monthlyRevenue',
            'monthlyExpenses',
            'collectionTrend'
        ));
    }
}
