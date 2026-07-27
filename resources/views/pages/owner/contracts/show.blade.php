@extends('layouts.owner')

@section('title', 'Contrat — ' . $contract->tenant_name)

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.contracts.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux contrats
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $contract->tenant_name }}</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $contract->property->title }}</p>
        </div>
        <form action="{{ route('owner.contracts.generate-rents', $contract) }}" method="POST">
            @csrf
            <button type="submit"
                class="flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                Régénérer l'échéancier
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Contract Info --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Informations du bail</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Statut</dt>
                    <dd>
                        @php
                            $sc = ['active' => 'emerald', 'pending' => 'amber', 'terminated' => 'red'];
                            $sl = ['active' => 'Actif', 'pending' => 'En attente', 'terminated' => 'Résilié'];
                            $c = $sc[$contract->status] ?? 'gray';
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full bg-{{ $c }}-100 dark:bg-{{ $c }}-900/30 text-{{ $c }}-600 dark:text-{{ $c }}-400">
                            {{ $sl[$contract->status] ?? $contract->status }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Début</dt>
                    <dd class="text-gray-800 dark:text-white font-medium">{{ $contract->start_date->format('d/m/Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Fin</dt>
                    <dd class="text-gray-800 dark:text-white font-medium">{{ $contract->end_date?->format('d/m/Y') ?? 'Illimité' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Loyer mensuel</dt>
                    <dd class="text-gray-800 dark:text-white font-bold">{{ number_format($contract->monthly_rent, 0, ',', ' ') }} FCFA</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Dépôt garantie</dt>
                    <dd class="text-gray-800 dark:text-white">{{ $contract->deposit ? number_format($contract->deposit, 0, ',', ' ') . ' FCFA' : '—' }}</dd>
                </div>
            </dl>

            <div class="border-t border-gray-100 dark:border-gray-700 mt-4 pt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">Contact locataire</p>
                <p class="text-sm text-gray-800 dark:text-white">{{ $contract->tenant_phone ?? '—' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $contract->tenant_email ?? '—' }}</p>
            </div>
        </div>

        {{-- Stats --}}
        @php
            $paidCount = $contract->rentPayments->where('status', 'paid')->count();
            $totalCount = $contract->rentPayments->count();
            $collectedTotal = $contract->rentPayments->sum('amount_paid');
            $dueTotal = $contract->rentPayments->sum('amount_due');
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Bilan de collecte</h3>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-3 text-center">
                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $paidCount }}/{{ $totalCount }}</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400">Loyers payés</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 text-center">
                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($collectedTotal / 1000, 0) }}k</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400">FCFA perçus</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Rent Payments Schedule --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-white">Échéancier de loyer</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase bg-gray-50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-5 py-3 font-medium">Période</th>
                        <th class="px-5 py-3 font-medium text-right">Montant dû</th>
                        <th class="px-5 py-3 font-medium text-right">Montant payé</th>
                        <th class="px-5 py-3 font-medium">Échéance</th>
                        <th class="px-5 py-3 font-medium text-center">Statut</th>
                        <th class="px-5 py-3 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($contract->rentPayments->sortBy(['year', 'month']) as $payment)
                        @php
                            $pc = ['unpaid' => 'gray', 'paid' => 'emerald', 'late' => 'red', 'partial' => 'amber'];
                            $pl = ['unpaid' => 'Non payé', 'paid' => 'Payé', 'late' => 'En retard', 'partial' => 'Partiel'];
                            $payColor = $pc[$payment->status] ?? 'gray';
                            $months = ['', 'Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">
                                {{ $months[$payment->month] ?? $payment->month }} {{ $payment->year }}
                            </td>
                            <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">{{ number_format($payment->amount_due, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">{{ number_format($payment->amount_paid, 0, ',', ' ') }}</td>
                            <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $payment->due_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-xs px-2 py-1 rounded-full bg-{{ $payColor }}-100 dark:bg-{{ $payColor }}-900/30 text-{{ $payColor }}-600 dark:text-{{ $payColor }}-400">
                                    {{ $pl[$payment->status] ?? $payment->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <form action="{{ route('owner.rent-payments.toggle-paid', $payment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs px-3 py-1 rounded-lg border {{ $payment->status === 'paid' ? 'border-red-200 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' : 'border-emerald-200 dark:border-emerald-700 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20' }} transition">
                                        {{ $payment->status === 'paid' ? 'Annuler' : 'Marquer payé' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
                                Aucun échéancier. <a href="{{ route('owner.contracts.generate-rents', $contract) }}" class="text-primary hover:underline">Générer</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
