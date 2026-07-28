<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Intervention;
use App\Models\RentPayment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $contracts = Contract::where('tenant_email', $user->email)
            ->with('property:id,title,address')
            ->get();
        $activeContract = $contracts->where('status', 'active')->first();

        $totalPaid = 0;
        $totalDue = 0;
        $nextPayment = null;
        $latePayments = collect();

        if ($activeContract) {
            $payments = RentPayment::where('contract_id', $activeContract->id)->get();
            $totalPaid = $payments->where('status', 'paid')->sum('amount_paid');
            $totalDue = $payments->sum('amount_due');
            $nextPayment = RentPayment::where('contract_id', $activeContract->id)
                ->where('status', 'unpaid')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->first();
            $latePayments = RentPayment::where('contract_id', $activeContract->id)
                ->where('status', 'unpaid')
                ->where('due_date', '<', now())
                ->orderBy('due_date')
                ->get();
        }

        $interventions = collect();
        if ($activeContract) {
            $interventions = Intervention::where('property_id', $activeContract->property_id)
                ->latest()
                ->take(5)
                ->get();
        }

        $propertyIds = $contracts->pluck('property_id');
        $documents = Document::whereIn('property_id', $propertyIds)
            ->latest()
            ->take(5)
            ->get();

        return view('pages.tenant.dashboard', compact(
            'contracts', 'activeContract', 'totalPaid', 'totalDue', 'nextPayment', 'latePayments', 'interventions', 'documents'
        ));
    }

    public function contracts()
    {
        $user = auth()->user();
        $contracts = Contract::where('tenant_email', $user->email)
            ->with('property:id,title,address')
            ->latest()
            ->get();

        return view('pages.tenant.contracts', compact('contracts'));
    }

    public function payments()
    {
        $user = auth()->user();
        $contract = Contract::where('tenant_email', $user->email)
            ->where('status', 'active')
            ->first();

        $payments = collect();
        if ($contract) {
            $payments = RentPayment::where('contract_id', $contract->id)
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->get();
        }

        return view('pages.tenant.payments', compact('contract', 'payments'));
    }

    public function interventions()
    {
        $user = auth()->user();
        $contract = Contract::where('tenant_email', $user->email)
            ->where('status', 'active')
            ->first();

        $interventions = collect();
        if ($contract) {
            $interventions = Intervention::where('property_id', $contract->property_id)
                ->latest()
                ->paginate(15);
        }

        return view('pages.tenant.interventions', compact('contract', 'interventions'));
    }

    public function documents()
    {
        $user = auth()->user();
        $contracts = Contract::where('tenant_email', $user->email)->get();
        $propertyIds = $contracts->pluck('property_id');

        $documents = Document::whereIn('property_id', $propertyIds)
            ->latest()
            ->paginate(15);

        return view('pages.tenant.documents', compact('documents'));
    }
}
