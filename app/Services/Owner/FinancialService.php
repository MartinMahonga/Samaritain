<?php

namespace App\Services\Owner;

use App\Models\Contract;
use App\Models\Intervention;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\RentPayment;
use Illuminate\Support\Collection;

class FinancialService
{
    private int $userId;

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId ?? auth()->id();
    }

    public function getPropertyIds(): array
    {
        return Property::where('created_by', $this->userId)->pluck('id')->toArray();
    }

    public function getProperties(): Collection
    {
        return Property::where('created_by', $this->userId)->get();
    }

    public function getScopedPropertyIds(?int $filterPropertyId = null): array
    {
        $propertyIds = $this->getPropertyIds();
        if ($filterPropertyId) {
            return [intval($filterPropertyId)];
        }

        return $propertyIds;
    }

    public function getTotalRevenue(array $propertyIds): int
    {
        return RentPayment::whereIn('contract_id', $this->getContractIds($propertyIds))
            ->where('status', 'paid')
            ->sum('amount_paid');
    }

    public function getTotalExpenses(array $propertyIds): int
    {
        return Intervention::whereIn('property_id', $propertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', false)
            ->sum('cost');
    }

    public function getTotalRenovations(array $propertyIds): int
    {
        return Intervention::whereIn('property_id', $propertyIds)
            ->where('status', 'completed')
            ->where('is_renovation', true)
            ->sum('cost');
    }

    public function getTotalUtilities(array $propertyIds): int
    {
        return Invoice::whereIn('property_id', $propertyIds)
            ->where('status', 'paid')
            ->sum('amount');
    }

    public function getNetProfit(array $propertyIds): int
    {
        return $this->getTotalRevenue($propertyIds)
            - $this->getTotalExpenses($propertyIds)
            - $this->getTotalRenovations($propertyIds)
            - $this->getTotalUtilities($propertyIds);
    }

    public function getCollectionRate(array $propertyIds): int
    {
        $contractIds = $this->getContractIds($propertyIds);
        $totalRentsDue = RentPayment::whereIn('contract_id', $contractIds)->sum('amount_due');
        $totalRentsPaid = RentPayment::whereIn('contract_id', $contractIds)->sum('amount_paid');

        return $totalRentsDue > 0 ? round(($totalRentsPaid / $totalRentsDue) * 100) : 0;
    }

    public function getMonthlyData(array $propertyIds, int $year): array
    {
        $months = range(1, 12);
        $monthlyRevenue = [];
        $monthlyExpenses = [];
        $monthlyUtilities = [];
        $monthlyProfits = [];

        foreach ($months as $month) {
            $rev = RentPayment::whereIn('contract_id', $this->getContractIds($propertyIds))
                ->where('month', $month)->where('year', $year)
                ->where('status', 'paid')->sum('amount_paid');

            $exp = Intervention::whereIn('property_id', $propertyIds)
                ->where('status', 'completed')->where('is_renovation', false)
                ->whereMonth('created_at', $month)->whereYear('created_at', $year)
                ->sum('cost');

            $ren = Intervention::whereIn('property_id', $propertyIds)
                ->where('status', 'completed')->where('is_renovation', true)
                ->whereMonth('created_at', $month)->whereYear('created_at', $year)
                ->sum('cost');

            $ut = Invoice::whereIn('property_id', $propertyIds)
                ->where('status', 'paid')
                ->whereMonth('paid_at', $month)->whereYear('paid_at', $year)
                ->sum('amount');

            $monthlyRevenue[] = $rev;
            $monthlyExpenses[] = $exp + $ren;
            $monthlyUtilities[] = $ut;
            $monthlyProfits[] = $rev - $exp - $ren - $ut;
        }

        return [$monthlyRevenue, $monthlyExpenses, $monthlyUtilities, $monthlyProfits];
    }

    public function getIncomeByProperty(Collection $properties): array
    {
        $incomeByProperty = [];
        foreach ($properties as $prop) {
            $incomeByProperty[] = [
                'title' => $prop->title,
                'amount' => RentPayment::whereIn('contract_id', Contract::where('property_id', $prop->id)->pluck('id'))
                    ->where('status', 'paid')->sum('amount_paid'),
            ];
        }

        return $incomeByProperty;
    }

    public function getTransactions(array $propertyIds): Collection
    {
        $rents = RentPayment::whereIn('contract_id', $this->getContractIds($propertyIds))
            ->where('status', 'paid')
            ->with('contract.property')
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

        $maintenance = Intervention::whereIn('property_id', $propertyIds)
            ->where('status', 'completed')->with('property')->get()
            ->map(fn ($int) => [
                'date' => $int->updated_at,
                'type' => $int->is_renovation ? 'Rénovation' : 'Maintenance',
                'category' => ucfirst($int->category),
                'property' => $int->property->title,
                'description' => $int->title,
                'amount' => $int->cost,
                'is_income' => false,
            ]);

        $invoiceExpenses = Invoice::whereIn('property_id', $propertyIds)
            ->where('status', 'paid')->with('property')->get()
            ->map(fn ($inv) => [
                'date' => $inv->paid_at ?? $inv->updated_at,
                'type' => 'Facture',
                'category' => ucfirst($inv->type),
                'property' => $inv->property->title,
                'description' => "Paiement charge / facture de {$inv->type}",
                'amount' => $inv->amount,
                'is_income' => false,
            ]);

        return $rents->concat($maintenance)->concat($invoiceExpenses)->sortByDesc('date');
    }

    public function getPropertyStats(Collection $properties): array
    {
        $propertyStats = [];
        foreach ($properties as $prop) {
            $propRevenue = RentPayment::whereIn('contract_id', Contract::where('property_id', $prop->id)->pluck('id'))
                ->where('status', 'paid')->sum('amount_paid');

            $propExpenses = Intervention::where('property_id', $prop->id)
                ->where('status', 'completed')->sum('cost')
                + Invoice::where('property_id', $prop->id)
                    ->where('status', 'paid')->sum('amount');

            $hasActiveContract = Contract::where('property_id', $prop->id)->where('status', 'active')->exists();

            $propertyStats[] = [
                'property' => $prop,
                'revenue' => $propRevenue,
                'expense' => $propExpenses,
                'profit' => $propRevenue - $propExpenses,
                'status' => $hasActiveContract ? 'Loué' : 'Disponible',
            ];
        }

        return $propertyStats;
    }

    private function getContractIds(array $propertyIds): array
    {
        return Contract::whereIn('property_id', $propertyIds)->pluck('id')->toArray();
    }
}
