@extends('layouts.owner')

@section('title', 'Dashboard Financier')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Finances & Statistiques</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Vue d'ensemble de vos revenus et dépenses immobilières.</p>
    </div>
    <a href="{{ route('owner.financial.export', request()->query()) }}"
        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition shrink-0">
        <i data-lucide="download" class="w-4 h-4"></i>
        Exporter CSV
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('owner.financial') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-3">
        <select name="property_id" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Toutes les propriétés</option>
            @foreach($properties as $p)
                <option value="{{ $p->id }}" @selected($filterPropertyId == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
        <select name="year" class="w-full sm:w-40 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            @foreach(range(date('Y'), date('Y') - 4) as $y)
                <option value="{{ $y }}" @selected($filterYear == $y)>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition shrink-0">Filtrer</button>
    </div>
</form>

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between mb-2">
            <i data-lucide="trending-up" class="w-5 h-5 opacity-80"></i>
            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Revenus</span>
        </div>
        <div class="text-xl font-bold">{{ number_format($totalRevenue, 0, ',', ' ') }}</div>
        <div class="text-xs opacity-80 mt-1">FCFA perçus</div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between mb-2">
            <i data-lucide="wrench" class="w-5 h-5 opacity-80"></i>
            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Maintenance</span>
        </div>
        <div class="text-xl font-bold">{{ number_format($totalExpenses, 0, ',', ' ') }}</div>
        <div class="text-xs opacity-80 mt-1">FCFA dépensés</div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between mb-2">
            <i data-lucide="hard-hat" class="w-5 h-5 opacity-80"></i>
            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Rénovations</span>
        </div>
        <div class="text-xl font-bold">{{ number_format($totalRenovations, 0, ',', ' ') }}</div>
        <div class="text-xs opacity-80 mt-1">FCFA investis</div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between mb-2">
            <i data-lucide="receipt" class="w-5 h-5 opacity-80"></i>
            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Charges</span>
        </div>
        <div class="text-xl font-bold">{{ number_format($totalUtilities, 0, ',', ' ') }}</div>
        <div class="text-xs opacity-80 mt-1">FCFA de charges</div>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between mb-2">
            <i data-lucide="wallet" class="w-5 h-5 opacity-80"></i>
            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Bénéfice net</span>
        </div>
        <div class="text-xl font-bold">{{ number_format($netProfit, 0, ',', ' ') }}</div>
        <div class="text-xs opacity-80 mt-1">FCFA net</div>
    </div>

    <div class="bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between mb-2">
            <i data-lucide="percent" class="w-5 h-5 opacity-80"></i>
            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Collecte</span>
        </div>
        <div class="text-xl font-bold">{{ $collectionRate }}%</div>
        <div class="text-xs opacity-80 mt-1">taux de collecte</div>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Monthly Chart --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Revenus & Dépenses — {{ $filterYear }}</h3>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    {{-- Income by Property --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Revenus par bien</h3>
        <canvas id="propertyChart" height="220"></canvas>
    </div>
</div>

{{-- Property Stats Table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-8">
    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Synthèse par propriété</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-3 font-medium">Bien</th>
                    <th class="pb-3 font-medium text-right">Revenus (FCFA)</th>
                    <th class="pb-3 font-medium text-right">Charges (FCFA)</th>
                    <th class="pb-3 font-medium text-right">Bénéfice net</th>
                    <th class="pb-3 font-medium text-center">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($propertyStats as $stat)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 font-medium text-gray-800 dark:text-white">{{ $stat['property']->title }}</td>
                        <td class="py-3 text-right text-emerald-600 dark:text-emerald-400">{{ number_format($stat['revenue'], 0, ',', ' ') }}</td>
                        <td class="py-3 text-right text-red-500 dark:text-red-400">{{ number_format($stat['expense'], 0, ',', ' ') }}</td>
                        <td class="py-3 text-right font-semibold {{ $stat['profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                            {{ number_format($stat['profit'], 0, ',', ' ') }}
                        </td>
                        <td class="py-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full {{ $stat['status'] === 'Loué' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                {{ $stat['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500">Aucune propriété trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Transaction History --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800 dark:text-white">Historique des transactions</h3>
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $totalTransactionsCount }} transactions</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase border-b border-gray-100 dark:border-gray-700">
                    <th class="pb-3 font-medium">Date</th>
                    <th class="pb-3 font-medium">Type</th>
                    <th class="pb-3 font-medium">Bien</th>
                    <th class="pb-3 font-medium">Détail</th>
                    <th class="pb-3 font-medium text-right">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($paginatedTransactions as $tx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                            {{ is_string($tx['date']) ? $tx['date'] : $tx['date']->format('d/m/Y') }}
                        </td>
                        <td class="py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $tx['is_income'] ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                                {{ $tx['type'] }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-700 dark:text-gray-300 text-sm">{{ $tx['property'] }}</td>
                        <td class="py-3 text-gray-500 dark:text-gray-400 text-xs max-w-xs truncate">{{ $tx['description'] }}</td>
                        <td class="py-3 text-right font-semibold {{ $tx['is_income'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                            {{ $tx['is_income'] ? '+' : '-' }}{{ number_format(abs($tx['amount']), 0, ',', ' ') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500">Aucune transaction enregistrée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Simple pagination --}}
    @if($totalTransactionsCount > $perPage)
        <div class="flex justify-center gap-2 mt-4">
            @if($page > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" class="px-3 py-1 text-sm rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">← Préc.</a>
            @endif
            @if($page * $perPage < $totalTransactionsCount)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" class="px-3 py-1 text-sm rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">Suiv. →</a>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    const textColor = isDark ? '#9ca3af' : '#6b7280';

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'Revenus',
                    data: @json($monthlyRevenue),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderRadius: 4,
                },
                {
                    label: 'Dépenses',
                    data: @json($monthlyExpenses),
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderRadius: 4,
                },
                {
                    label: 'Charges',
                    data: @json($monthlyUtilities),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderRadius: 4,
                },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: textColor } } },
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor } },
                y: { ticks: { color: textColor }, grid: { color: gridColor } }
            }
        }
    });

    // Property Doughnut Chart
    const propCtx = document.getElementById('propertyChart').getContext('2d');
    new Chart(propCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_column($incomeByProperty, 'title')),
            datasets: [{
                data: @json(array_column($incomeByProperty, 'amount')),
                backgroundColor: ['#10b981','#f59e0b','#3b82f6','#8b5cf6','#ef4444','#06b6d4'],
                borderWidth: 2,
                borderColor: isDark ? '#1f2937' : '#ffffff',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { color: textColor, padding: 10, boxWidth: 12 } } }
        }
    });
</script>
@endpush
