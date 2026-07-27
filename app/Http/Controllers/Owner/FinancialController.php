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
        $properties = Property::where('created_by', $userId)->get();
        $propertyIds = $properties->pluck('id')->toArray();

        // Get filter inputs
        $filterPropertyId = $request->input('property_id');
        $filterYear = $request->input('year', now()->year);

        // Scope properties based on filter
        $scopedPropertyIds = $propertyIds;
        if ($filterPropertyId) {
            $scopedPropertyIds = [intval($filterPropertyId)];
        }

        // 1. KPI Calculations (Global or Filtered)
        // Revenues: Paid rents
        $totalRevenue = RentPayment::whereIn('contract_id', Contract::whereIn('property_id', $scopedPropertyIds)->pluck('id'))
            ->where('status', 'paid')
            ->sum('amount_paid');

        // Expenses: Completed maintenance (excluding renovations)
        $totalExpenses = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', false)
            ->sum('cost');

        // Renovation costs
        $totalRenovations = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', true)
            ->sum('cost');

        // Utilities/Charges: Paid invoices
        $totalUtilities = Invoice::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'paid')
            ->sum('amount');

        // Profit
        $netProfit = $totalRevenue - $totalExpenses - $totalRenovations - $totalUtilities;

        // Collection rate: paid rents vs total rents due
        $totalRentsDueQuery = RentPayment::whereIn('contract_id', Contract::whereIn('property_id', $scopedPropertyIds)->pluck('id'));
        $totalRentsDue = $totalRentsDueQuery->sum('amount_due');
        $totalRentsPaid = $totalRentsDueQuery->sum('amount_paid');
        $collectionRate = $totalRentsDue > 0 ? round(($totalRentsPaid / $totalRentsDue) * 100) : 0;

        // 2. Chart Data Generation (Monthly for selected year)
        $months = range(1, 12);
        $monthlyRevenue = [];
        $monthlyExpenses = [];
        $monthlyUtilities = [];
        $monthlyProfits = [];

        foreach ($months as $month) {
            $rev = RentPayment::whereIn('contract_id', Contract::whereIn('property_id', $scopedPropertyIds)->pluck('id'))
                ->where('month', $month)
                ->where('year', $filterYear)
                ->where('status', 'paid')
                ->sum('amount_paid');

            $exp = Intervention::whereIn('property_id', $scopedPropertyIds)
                ->where('status', 'completed')
                ->where('is_renovation', false)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $filterYear)
                ->sum('cost');

            $ut = Invoice::whereIn('property_id', $scopedPropertyIds)
                ->where('status', 'paid')
                ->whereMonth('paid_at', $month)
                ->whereYear('paid_at', $filterYear)
                ->sum('amount');

            $ren = Intervention::whereIn('property_id', $scopedPropertyIds)
                ->where('status', 'completed')
                ->where('is_renovation', true)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $filterYear)
                ->sum('cost');

            $monthlyRevenue[] = $rev;
            $monthlyExpenses[] = $exp + $ren;
            $monthlyUtilities[] = $ut;
            $monthlyProfits[] = $rev - $exp - $ren - $ut;
        }

        // Income by property
        $incomeByProperty = [];
        foreach ($properties as $prop) {
            $income = RentPayment::whereIn('contract_id', Contract::where('property_id', $prop->id)->pluck('id'))
                ->where('status', 'paid')
                ->sum('amount_paid');
            
            $incomeByProperty[] = [
                'title' => $prop->title,
                'amount' => $income
            ];
        }

        // 3. Detailed Transactions list
        // Let's retrieve all rent payments, invoices and interventions, map them as standard transaction items and order them.
        $rents = RentPayment::whereIn('contract_id', Contract::whereIn('property_id', $scopedPropertyIds)->pluck('id'))
            ->where('status', 'paid')
            ->with('contract.property')
            ->get()
            ->map(function ($rent) {
                return [
                    'date' => $rent->paid_at ?? $rent->updated_at,
                    'type' => 'Loyer',
                    'category' => 'Revenu locatif',
                    'property' => $rent->contract->property->title,
                    'description' => "Loyer " . $rent->month . "/" . $rent->year . " - Locataire: " . $rent->contract->tenant_name,
                    'amount' => $rent->amount_paid,
                    'is_income' => true,
                ];
            });

        $maintenanceExpenses = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->with('property')
            ->get()
            ->map(function ($int) {
                return [
                    'date' => $int->updated_at,
                    'type' => $int->is_renovation ? 'Rénovation' : 'Maintenance',
                    'category' => ucfirst($int->category),
                    'property' => $int->property->title,
                    'description' => $int->title,
                    'amount' => $int->cost,
                    'is_income' => false,
                ];
            });

        $invoiceExpenses = Invoice::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'paid')
            ->with('property')
            ->get()
            ->map(function ($inv) {
                return [
                    'date' => $inv->paid_at ?? $inv->updated_at,
                    'type' => 'Facture',
                    'category' => ucfirst($inv->type),
                    'property' => $inv->property->title,
                    'description' => "Paiement charge / facture de " . $inv->type,
                    'amount' => $inv->amount,
                    'is_income' => false,
                ];
            });

        $transactions = $rents->concat($maintenanceExpenses)->concat($invoiceExpenses)->sortByDesc('date');

        // Paginate manually
        $page = $request->input('page', 1);
        $perPage = 15;
        $totalTransactionsCount = $transactions->count();
        $paginatedTransactions = $transactions->slice(($page - 1) * $perPage, $perPage)->all();

        // 4. Statistics by Property table
        $propertyStats = [];
        foreach ($properties as $prop) {
            $propRevenue = RentPayment::whereIn('contract_id', Contract::where('property_id', $prop->id)->pluck('id'))
                ->where('status', 'paid')
                ->sum('amount_paid');
            
            $propMaintenance = Intervention::where('property_id', $prop->id)
                ->where('status', 'completed')
                ->sum('cost');

            $propInvoices = Invoice::where('property_id', $prop->id)
                ->where('status', 'paid')
                ->sum('amount');

            $propNet = $propRevenue - $propMaintenance - $propInvoices;

            $hasActiveContract = Contract::where('property_id', $prop->id)->where('status', 'active')->exists();

            $propertyStats[] = [
                'property' => $prop,
                'revenue' => $propRevenue,
                'expense' => $propMaintenance + $propInvoices,
                'profit' => $propNet,
                'status' => $hasActiveContract ? 'Loué' : 'Disponible'
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
        $properties = Property::where('created_by', $userId)->get();
        $propertyIds = $properties->pluck('id')->toArray();

        $filterPropertyId = $request->input('property_id');
        $scopedPropertyIds = $propertyIds;
        if ($filterPropertyId) {
            $scopedPropertyIds = [intval($filterPropertyId)];
        }

        // Fetch same data as transactions
        $rents = RentPayment::whereIn('contract_id', Contract::whereIn('property_id', $scopedPropertyIds)->pluck('id'))
            ->where('status', 'paid')
            ->with('contract.property')
            ->get()
            ->map(function ($rent) {
                return [
                    'date' => ($rent->paid_at ?? $rent->updated_at)->format('d/m/Y'),
                    'type' => 'Revenu',
                    'category' => 'Loyer',
                    'property' => $rent->contract->property->title,
                    'description' => "Loyer " . $rent->month . "/" . $rent->year . " - Locataire: " . $rent->contract->tenant_name,
                    'amount' => $rent->amount_paid,
                ];
            });

        $maintenanceExpenses = Intervention::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'completed')
            ->with('property')
            ->get()
            ->map(function ($int) {
                return [
                    'date' => $int->updated_at->format('d/m/Y'),
                    'type' => 'Dépense',
                    'category' => $int->is_renovation ? 'Rénovation' : 'Maintenance',
                    'property' => $int->property->title,
                    'description' => $int->title . ' (' . $int->category . ')',
                    'amount' => -$int->cost,
                ];
            });

        $invoiceExpenses = Invoice::whereIn('property_id', $scopedPropertyIds)
            ->where('status', 'paid')
            ->with('property')
            ->get()
            ->map(function ($inv) {
                return [
                    'date' => ($inv->paid_at ?? $inv->updated_at)->format('d/m/Y'),
                    'type' => 'Dépense',
                    'category' => 'Facture ' . $inv->type,
                    'property' => $inv->property->title,
                    'description' => "Paiement charge " . $inv->type,
                    'amount' => -$inv->amount,
                ];
            });

        $transactions = $rents->concat($maintenanceExpenses)->concat($invoiceExpenses)->sortByDesc('date');

        // CSV generation
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Rapport_Financier_Samaritain_' . date('Ymd') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Date', 'Type', 'Catégorie', 'Bien', 'Description', 'Montant (FCFA)']);

            foreach ($transactions as $row) {
                fputcsv($file, [
                    $row['date'],
                    $row['type'],
                    $row['category'],
                    $row['property'],
                    $row['description'],
                    $row['amount']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
