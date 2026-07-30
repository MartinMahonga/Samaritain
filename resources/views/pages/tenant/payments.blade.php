@extends('layouts.tenant')

@section('title', 'Mes Paiements')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mes Paiements</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Historique des loyers payés et à venir.</p>
</div>

@if($contract)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-xl p-4">
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($payments->where('status', 'paid')->sum('amount_paid'), 0, ',', ' ') }}</p>
            <p class="text-sm text-emerald-500">FCFA payés</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $payments->count() }}</p>
            <p class="text-sm text-blue-500">Total échéances</p>
        </div>
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-xl p-4">
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $payments->where('status', 'unpaid')->count() }}</p>
            <p class="text-sm text-amber-500">En attente</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/30 border-b dark:border-gray-700">
                        <th class="px-5 py-3">Période</th>
                        <th class="px-5 py-3 text-right">Montant dû</th>
                        <th class="px-5 py-3 text-right">Montant payé</th>
                        <th class="px-5 py-3">Échéance</th>
                        <th class="px-5 py-3 text-center">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($payments->sortBy(['year', 'month']) as $payment)
                        @php
                            $months = ['', 'Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">
                                {{ $months[$payment->month] ?? $payment->month }} {{ $payment->year }}
                            </td>
                            <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">
                                {{ number_format($payment->amount_due, 0, ',', ' ') }}
                            </td>
                            <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">
                                {{ number_format($payment->amount_paid, 0, ',', ' ') }}
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ $payment->due_date->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($payment->status === 'paid')
                                    <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2 py-1 rounded-full">Payé</span>
                                @elseif($payment->status === 'unpaid' && $payment->due_date < now())
                                    <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded-full">En retard</span>
                                @elseif($payment->status === 'partial')
                                    <span class="text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded-full">Partiel</span>
                                @else
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Non payé</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
        <i data-lucide="file-text" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
        <p class="text-gray-400 dark:text-gray-500">Aucun contrat actif trouvé.</p>
    </div>
@endif
@endsection