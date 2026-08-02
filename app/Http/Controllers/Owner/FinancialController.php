<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Intervention;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\RentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $properties = Property::where('created_by', $userId)->get(['id', 'title']);
        $propertyIds = $properties->pluck('id')->toArray();

        $filterPropertyId = $request->input('property_id');
        $filterYear = (int) $request->input('year', now()->year);

        $scopedPropertyIds = $filterPropertyId ? [intval($filterPropertyId)] : $propertyIds;

        // Pre-compute contract IDs once
        $contractIds = Contract::whereIn('property_id', $scopedPropertyIds)->pluck('id')->toArray();

        // 1. KPI Calculations - single aggregated query
        $kpi = RentPayment::whereIn('contract_id', $contractIds)
            ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN amount_paid ELSE 0 END), 0) as total_revenue', ['paid'])
            ->selectRaw('COALESCE(SUM(amount_due), 0) as total_due')
            ->selectRaw('COALESCE(SUM(amount_paid), 0) as total_paid')
            ->first();

        $totalRevenue = $kpi->total_revenue ?? 0;
        $totalRentsDue = $kpi->total_due ?? 0;
        $totalRentsPaid = $kpi->total_paid ?? 0;

        $totalExpenses = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', false)
            ->sum('cost');

        $totalRenovations = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', true)
            ->sum('cost');

        $totalUtilities = Invoice::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'paid')
            ->sum('amount');

        $netProfit = $totalRevenue - $totalExpenses - $totalRenovations - $totalUtilities;
        $collectionRate = $totalRentsDue > 0 ? round(($totalRentsPaid / $totalRentsDue) * 100) : 0;

        // 2. Monthly data - 4 queries instead of 48 (12 months × 4 types)
        $monthlyRevenue = array_fill(0, 12, 0);
        $monthlyExpenses = array_fill(0, 12, 0);
        $monthlyUtilities = array_fill(0, 12, 0);
        $monthlyProfits = array_fill(0, 12, 0);

        // Monthly rents
        $rentsByMonth = RentPayment::whereIn('contract_id', $contractIds)
            ->where('year', $filterYear)
            ->where('status', 'paid')
            ->selectRaw('month, COALESCE(SUM(amount_paid), 0) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        foreach ($rentsByMonth as $month => $total) {
            $monthlyRevenue[$month - 1] = (int) $total;
        }

        // Monthly expenses (maintenance)
        $expensesByMonth = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', false)
            ->whereYear('created_at', $filterYear)
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(cost), 0) as total')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Monthly renovations
        $renovationsByMonth = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', true)
            ->whereYear('created_at', $filterYear)
            ->selectRaw('MONTH(created_at) as month, COALESCE(SUM(cost), 0) as total')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Monthly utilities
        $utilitiesByMonth = Invoice::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'paid')
            ->whereYear('paid_at', $filterYear)
            ->selectRaw('MONTH(paid_at) as month, COALESCE(SUM(amount), 0) as total')
            ->groupBy(DB::raw('MONTH(paid_at)'))
            ->pluck('total', 'month');

        foreach (range(0, 11) as $i) {
            $month = $i + 1;
            $exp = (int) ($expensesByMonth[$month] ?? 0);
            $ren = (int) ($renovationsByMonth[$month] ?? 0);
            $ut = (int) ($utilitiesByMonth[$month] ?? 0);
            $rev = $monthlyRevenue[$i];

            $monthlyExpenses[$i] = $exp + $ren;
            $monthlyUtilities[$i] = $ut;
            $monthlyProfits[$i] = $rev - $exp - $ren - $ut;
        }

        // 3. Income by property - single query
        $contractIdsByProperty = Contract::whereIn('property_id', $propertyIds)
            ->selectRaw('property_id, GROUP_CONCAT(id) as contract_ids')
            ->groupBy('property_id')
            ->pluck('contract_ids', 'property_id');

        $incomeByProperty = [];
        $propertyIncome = RentPayment::whereIn('contract_id', $contractIds)
            ->where('status', 'paid')
            ->selectRaw('contract_id, COALESCE(SUM(amount_paid), 0) as total')
            ->groupBy('contract_id')
            ->pluck('total', 'contract_id');

        foreach ($properties as $prop) {
            $propContractIds = isset($contractIdsByProperty[$prop->id])
                ? explode(',', $contractIdsByProperty[$prop->id])
                : [];

            $income = 0;
            foreach ($propContractIds as $cid) {
                $income += (int) ($propertyIncome[$cid] ?? 0);
            }

            $incomeByProperty[] = [
                'title' => $prop->title,
                'amount' => $income,
            ];
        }

        // 4. Transactions
        $rents = RentPayment::whereIn('contract_id', $contractIds)
            ->where('status', 'paid')
            ->with('contract.property:id,title')
            ->get()
            ->map(fn ($rent) => [
                'date' => $rent->paid_at ?? $rent->updated_at,
                'type' => 'Loyer',
                'category' => 'Revenu locatif',
                'property' => $rent->contract->property->title,
                'description' => "Loyer {$rent->month}/{$rent->year} - Locataire: {$rent->contract->tenant_name}",
                'amount' => $rent->amount_paid,
                'is_income' => true,
            ]);

        $maintenanceExpenses = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->with('property:id,title')
            ->get()
            ->map(fn ($int) => [
                'date' => $int->updated_at,
                'type' => $int->is_renovation ? 'Rénovation' : 'Maintenance',
                'category' => ucfirst($int->category),
                'property' => $int->property->title,
                'description' => $int->title,
                'amount' => $int->cost,
                'is_income' => false,
            ]);

        $invoiceExpenses = Invoice::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'paid')
            ->with('property:id,title')
            ->get()
            ->map(fn ($inv) => [
                'date' => $inv->paid_at ?? $inv->updated_at,
                'type' => 'Facture',
                'category' => ucfirst($inv->type),
                'property' => $inv->property->title,
                'description' => "Paiement charge / facture de {$inv->type}",
                'amount' => $inv->amount,
                'is_income' => false,
            ]);

        $transactions = $rents->concat($maintenanceExpenses)->concat($invoiceExpenses)->sortByDesc('date');

        $page = (int) $request->input('page', 1);
        $perPage = 15;
        $totalTransactionsCount = $transactions->count();
        $paginatedTransactions = $transactions->slice(($page - 1) * $perPage, $perPage)->all();

        // 5. Property stats - single query per type
        $revenueByProperty = RentPayment::whereIn('contract_id', $contractIds)
            ->where('rp.status', 'paid')
            ->selectRaw('c.property_id, COALESCE(SUM(rp.amount_paid), 0) as total')
            ->from('rent_payments as rp')
            ->join('contracts as c', 'c.id', '=', 'rp.contract_id')
            ->groupBy('c.property_id')
            ->pluck('total', 'property_id');

        $expensesByProperty = Intervention::whereIn('property_id', $propertyIds)
            ->where('status', 'completed')
            ->selectRaw('property_id, COALESCE(SUM(cost), 0) as total')
            ->groupBy('property_id')
            ->pluck('total', 'property_id');

        $invoicesByProperty = Invoice::whereIn('property_id', $propertyIds)
            ->where('status', 'paid')
            ->selectRaw('property_id, COALESCE(SUM(amount), 0) as total')
            ->groupBy('property_id')
            ->pluck('total', 'property_id');

        $activeContractsByProperty = Contract::whereIn('property_id', $propertyIds)
            ->where('status', 'active')
            ->pluck('property_id')
            ->flip();

        $propertyStats = [];
        foreach ($properties as $prop) {
            $propRevenue = (int) ($revenueByProperty[$prop->id] ?? 0);
            $propExpenses = (int) ($expensesByProperty[$prop->id] ?? 0) + (int) ($invoicesByProperty[$prop->id] ?? 0);

            $propertyStats[] = [
                'property' => $prop,
                'revenue' => $propRevenue,
                'expense' => $propExpenses,
                'profit' => $propRevenue - $propExpenses,
                'status' => isset($activeContractsByProperty[$prop->id]) ? 'Loué' : 'Disponible',
            ];
        }

        return view('pages.owner.financial', compact(
            'properties',
            'filterPropertyId',
            'filterYear',
            'totalRevenue',
            'totalExpenses',
            'totalRenovations',
            'totalUtilities',
            'netProfit',
            'collectionRate',
            'monthlyRevenue',
            'monthlyExpenses',
            'monthlyUtilities',
            'monthlyProfits',
            'incomeByProperty',
            'paginatedTransactions',
            'totalTransactionsCount',
            'perPage',
            'page',
            'propertyStats'
        ));
    }

    public function export(Request $request)
    {
        $userId = auth()->id();
        $propertyIds = Property::where('created_by', $userId)->pluck('id')->toArray();

        $filterPropertyId = $request->input('property_id');
        $scopedPropertyIds = $filterPropertyId ? [intval($filterPropertyId)] : $propertyIds;
        $contractIds = Contract::whereIn('property_id', $scopedPropertyIds)->pluck('id')->toArray();

        $rents = RentPayment::whereIn('contract_id', $contractIds)
            ->where('status', 'paid')
            ->with('contract.property:id,title')
            ->get()
            ->map(fn ($rent) => [
                'date' => ($rent->paid_at ?? $rent->updated_at)->format('d/m/Y'),
                'type' => 'Revenu',
                'category' => 'Loyer',
                'property' => $rent->contract->property->title,
                'description' => "Loyer {$rent->month}/{$rent->year} - Locataire: {$rent->contract->tenant_name}",
                'amount' => $rent->amount_paid,
            ]);

        $maintenanceExpenses = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->with('property:id,title')
            ->get()
            ->map(fn ($int) => [
                'date' => $int->updated_at->format('d/m/Y'),
                'type' => 'Dépense',
                'category' => $int->is_renovation ? 'Rénovation' : 'Maintenance',
                'property' => $int->property->title,
                'description' => "{$int->title} ({$int->category})",
                'amount' => -$int->cost,
            ]);

        $invoiceExpenses = Invoice::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'paid')
            ->with('property:id,title')
            ->get()
            ->map(fn ($inv) => [
                'date' => ($inv->paid_at ?? $inv->updated_at)->format('d/m/Y'),
                'type' => 'Dépense',
                'category' => 'Facture '.$inv->type,
                'property' => $inv->property->title,
                'description' => "Paiement charge {$inv->type}",
                'amount' => -$inv->amount,
            ]);

        $transactions = $rents->concat($maintenanceExpenses)->concat($invoiceExpenses)->sortByDesc('date');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Rapport_Financier_Samaritain_'.date('Ymd').'.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Date', 'Type', 'Catégorie', 'Bien', 'Description', 'Montant (FCFA)']);

            foreach ($transactions as $row) {
                fputcsv($file, [$row['date'], $row['type'], $row['category'], $row['property'], $row['description'], $row['amount']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
