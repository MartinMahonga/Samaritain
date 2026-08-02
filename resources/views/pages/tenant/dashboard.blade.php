@extends('layouts.tenant')

@section('title', 'Mon Espace Locataire')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Bonjour, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Bienvenue dans votre espace locataire.</p>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Contrat actif --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-purple-100 dark:bg-purple-900/30 rounded-xl p-3">
                <i data-lucide="file-text" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Contrat</span>
        </div>
        @if($activeContract)
            <div class="text-lg font-bold text-gray-800 dark:text-white truncate">{{ $activeContract->property->title }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Depuis le {{ $activeContract->start_date->format('d/m/Y') }}</div>
            <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-2">✅ Contrat actif</div>
        @else
            <div class="text-lg font-bold text-gray-400 dark:text-gray-500">Aucun contrat</div>
            <div class="text-sm text-gray-400 dark:text-gray-400 mt-1">Vous n'avez pas de contrat actif</div>
        @endif
    </div>

    {{-- Loyers payés --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-xl p-3">
                <i data-lucide="banknote" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Loyers</span>
        </div>
        <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalPaid, 0, ',', ' ') }}</div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">FCFA payés</div>
        @if($totalDue > 0)
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                Taux de paiement : {{ round(($totalPaid / $totalDue) * 100) }}%
            </div>
        @endif
    </div>

    {{-- Prochain paiement --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-amber-100 dark:bg-amber-900/30 rounded-xl p-3">
                <i data-lucide="calendar" class="w-6 h-6 text-amber-600 dark:text-amber-400"></i>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Échéance</span>
        </div>
        @if($nextPayment)
            <div class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($nextPayment->amount_due, 0, ',', ' ') }} FCFA</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">À payer avant le {{ $nextPayment->due_date->format('d/m/Y') }}</div>
        @else
            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">À jour</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Aucun paiement en attente</div>
        @endif
    </div>

    {{-- Impayés --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-red-100 dark:bg-red-900/30 rounded-xl p-3">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Impayés</span>
        </div>
        <div class="text-2xl font-bold {{ $latePayments->count() > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
            {{ $latePayments->count() }}
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Paiement(s) en retard</div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="mb-8">
    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Actions rapides</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('tenant.contracts') }}" class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-purple-200 dark:hover:border-purple-700 transition group">
            <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-2 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40 transition">
                <i data-lucide="file-text" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-white text-sm">Mon contrat</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Voir les détails</p>
            </div>
        </a>

        <a href="{{ route('tenant.payments') }}" class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-emerald-200 dark:hover:border-emerald-700 transition group">
            <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-lg p-2 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/40 transition">
                <i data-lucide="banknote" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-white text-sm">Mes paiements</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Historique & échéances</p>
            </div>
        </a>

        <a href="{{ route('tenant.interventions') }}" class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-amber-200 dark:hover:border-amber-700 transition group">
            <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-2 group-hover:bg-amber-200 dark:group-hover:bg-amber-800/40 transition">
                <i data-lucide="wrench" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-white text-sm">Interventions</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Suivi des demandes</p>
            </div>
        </a>

        <a href="{{ route('tenant.documents') }}" class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-blue-700 transition group">
            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40 transition">
                <i data-lucide="folder-open" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800 dark:text-white text-sm">Documents</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Quittances & contrats</p>
            </div>
        </a>
    </div>
</div>

{{-- Quick Info --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Contract Details --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Mon contrat de location</h3>
        @if($activeContract)
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Bien</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $activeContract->property->title }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Adresse</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $activeContract->property->address }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Loyer mensuel</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-white">{{ number_format($activeContract->monthly_rent, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Dépôt de garantie</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $activeContract->deposit ? number_format($activeContract->deposit, 0, ',', ' ') . ' FCFA' : '—' }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Date de début</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $activeContract->start_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Date de fin</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $activeContract->end_date?->format('d/m/Y') ?? 'Tacite reconduction' }}</span>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <i data-lucide="file-text" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                <p class="text-gray-400 dark:text-gray-500">Aucun contrat actif trouvé pour votre compte.</p>
            </div>
        @endif
    </div>

    {{-- Recent Interventions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Interventions récentes</h3>
        <div class="space-y-3">
            @forelse($interventions as $intervention)
                <div class="flex items-center gap-3 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="wrench" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $intervention->title }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $intervention->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-400 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Aucune intervention</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent Payments --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800 dark:text-white">Derniers paiements</h3>
        <a href="{{ route('tenant.payments') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Voir tous →</a>
    </div>
    <div class="space-y-2">
        @php
            $statusLabels = ['unpaid' => 'Non payé', 'paid' => 'Payé', 'late' => 'En retard', 'partial' => 'Partiel'];
            $statusColors = ['unpaid' => 'gray', 'paid' => 'emerald', 'late' => 'red', 'partial' => 'amber'];
            $months = ['', 'Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
        @endphp
        @forelse($recentPayments->sortBy([['year', 'asc'], ['month', 'asc']]) as $payment)
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="banknote" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                            {{ $months[$payment->month] ?? $payment->month }} {{ $payment->year }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($payment->amount_due, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($payment->status === 'paid')
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2 py-1 rounded-full">
                            Payé
                        </span>
                    @elseif($payment->status === 'unpaid' && $payment->due_date < now())
                        <span class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-1 rounded-full">
                            En retard
                        </span>
                    @else
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
                            {{ $statusLabels[$payment->status] ?? $payment->status }}
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-6">
                <i data-lucide="banknote" class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                <p class="text-sm text-gray-400 dark:text-gray-500">Aucun paiement</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Recent Documents --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800 dark:text-white">Documents récents</h3>
        <a href="{{ route('tenant.documents') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Voir tous →</a>
    </div>
    <div class="space-y-2">
        @forelse($documents as $doc)
            <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="file" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white truncate max-w-xs">{{ $doc->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($doc->file_size / 1024, 1) }} Ko</p>
                    </div>
                </div>
                <a href="{{ route('tenant.documents.download', $doc) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 ml-4 shrink-0">
                    <i data-lucide="download" class="w-4 h-4"></i>
                </a>
            </div>
        @empty
            <div class="text-center py-6">
                <i data-lucide="folder-open" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                <p class="text-sm text-gray-400 dark:text-gray-500">Aucun document disponible</p>
            </div>
        @endforelse
    </div>
</div>
@endsection