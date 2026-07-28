@extends('layouts.owner')

@section('title', 'Portail Propriétaire')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Bonjour, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Voici un aperçu de votre patrimoine immobilier.</p>
</div>

{{-- KPI Cards avec skeleton loading --}}
<div x-data="{ loading: false }" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Total Biens --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-xl p-3">
                <i data-lucide="building-2" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Biens</span>
        </div>
        <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalProperties }}</div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Propriétés gérées</div>
        <div class="flex items-center gap-1 mt-2">
            <div class="h-1.5 flex-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $occupancyRate }}%"></div>
            </div>
            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">{{ $occupancyRate }}%</span>
        </div>
    </div>

    {{-- Loyers du mois --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-xl p-3">
                <i data-lucide="banknote" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Ce mois</span>
        </div>
        <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($rentCollectedThisMonth, 0, ',', ' ') }}</div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">FCFA perçus</div>
        <div class="flex items-center justify-between mt-2">
            <span class="text-xs text-amber-600 dark:text-amber-400">{{ number_format($rentPendingThisMonth, 0, ',', ' ') }} FCFA en attente</span>
            <span class="text-xs text-emerald-600 dark:text-emerald-400">
                @if($rentExpectedThisMonth > 0)
                    {{ round(($rentCollectedThisMonth / $rentExpectedThisMonth) * 100) }}%
                @else
                    0%
                @endif
            </span>
        </div>
    </div>

    {{-- Contrats actifs --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-purple-100 dark:bg-purple-900/30 rounded-xl p-3">
                <i data-lucide="file-text" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Contrats</span>
        </div>
        <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $activeContractsCount }}</div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Contrats actifs</div>
        <div class="text-xs text-blue-600 dark:text-blue-400 mt-2">
            <a href="{{ route('owner.contracts.index') }}" class="hover:underline">Voir tous →</a>
        </div>
    </div>

    {{-- Factures impayées --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-red-100 dark:bg-red-900/30 rounded-xl p-3">
                <i data-lucide="receipt" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
            </div>
            @if($unpaidInvoicesSum > 0)
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-500 dark:text-red-400"></i>
            @else
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 dark:text-emerald-400"></i>
            @endif
        </div>
        <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($unpaidInvoicesSum, 0, ',', ' ') }}</div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">FCFA de charges impayées</div>
        <div class="text-xs text-red-600 dark:text-red-400 mt-2">
            <a href="{{ route('owner.invoices.index', ['status' => 'unpaid']) }}" class="hover:underline">Voir les factures →</a>
        </div>
    </div>
</div>

{{-- Charts Section --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Monthly Revenue Chart --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800 dark:text-white">Revenus & Dépenses {{ now()->year }}</h3>
            <a href="{{ route('owner.financial') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Détails →</a>
        </div>
        <canvas id="dashboardChart" height="100"></canvas>
    </div>

    {{-- Collection Rate Trend --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Taux de collecte</h3>
        <canvas id="collectionChart" height="200"></canvas>
    </div>
</div>

{{-- Quick Actions & Intervention List --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Recent Interventions --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-white">Interventions en cours</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $pendingInterventions }} intervention(s) active(s) sur {{ $totalInterventions }} au total</p>
            </div>
            <a href="{{ route('owner.interventions.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Voir toutes →</a>
        </div>
        <div class="space-y-3">
            @forelse($recentInterventions as $intervention)
                @php
                    $urgencyColors = [
                        'low' => 'gray',
                        'medium' => 'amber',
                        'high' => 'orange',
                        'emergency' => 'red',
                    ];
                    $statusColors = [
                        'pending' => 'gray',
                        'approved' => 'blue',
                        'in_progress' => 'amber',
                        'completed' => 'emerald',
                        'cancelled' => 'red',
                    ];
                    $urgencyColor = $urgencyColors[$intervention->urgency] ?? 'gray';
                    $statusColor = $statusColors[$intervention->status] ?? 'gray';
                @endphp
                <a href="{{ route('owner.interventions.show', $intervention) }}" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-{{ $urgencyColor }}-100 dark:bg-{{ $urgencyColor }}-900/30 rounded-lg flex items-center justify-center shrink-0">
                            <i data-lucide="wrench" class="w-5 h-5 text-{{ $urgencyColor }}-600 dark:text-{{ $urgencyColor }}-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-white text-sm">{{ $intervention->title }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $intervention->property->title }}</p>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-900/30 text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 shrink-0">
                        {{ ucfirst(str_replace('_', ' ', $intervention->status)) }}
                    </span>
                </a>
            @empty
                <div class="text-center py-8">
                    <i data-lucide="check-circle" class="w-10 h-10 text-emerald-400 dark:text-emerald-500 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Aucune intervention en cours</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Actions rapides</h3>
        <div class="space-y-3">
            <a href="{{ route('owner.contracts.create') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition group">
                <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-2 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40 transition">
                    <i data-lucide="file-text" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800 dark:text-white text-sm">Nouveau contrat</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Créer un bail locatif</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-purple-500"></i>
            </a>

            <a href="{{ route('owner.interventions.create') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition group">
                <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-2 group-hover:bg-amber-200 dark:group-hover:bg-amber-800/40 transition">
                    <i data-lucide="wrench" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800 dark:text-white text-sm">Signaler une intervention</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Maintenance ou travaux</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-amber-500"></i>
            </a>

            <a href="{{ route('owner.invoices.create') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition group">
                <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-2 group-hover:bg-red-200 dark:group-hover:bg-red-800/40 transition">
                    <i data-lucide="receipt" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800 dark:text-white text-sm">Enregistrer une facture</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Eau, électricité, taxes...</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-red-500"></i>
            </a>

            <a href="{{ route('owner.inspections.create') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40 transition">
                    <i data-lucide="clipboard-check" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800 dark:text-white text-sm">État des lieux</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Entrée ou sortie</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-blue-500"></i>
            </a>

            <a href="{{ route('owner.financial') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition group">
                <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-lg p-2 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/40 transition">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800 dark:text-white text-sm">Tableau financier</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Revenus & dépenses</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-emerald-500"></i>
            </a>
        </div>
    </div>
</div>

{{-- Recent Documents --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800 dark:text-white">Documents récents</h3>
        <a href="{{ route('owner.documents.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Voir tous →</a>
    </div>
    <div class="space-y-2">
        @forelse($recentDocuments as $doc)
            @php
                $catIcons = ['invoice' => 'receipt', 'receipt' => 'check-square', 'quote' => 'file-edit', 'inspection' => 'clipboard-check', 'other' => 'paperclip'];
                $catColors = ['invoice' => 'red', 'receipt' => 'emerald', 'quote' => 'blue', 'inspection' => 'purple', 'other' => 'gray'];
                $icon = $catIcons[$doc->category] ?? 'paperclip';
                $color = $catColors[$doc->category] ?? 'gray';
            @endphp
            <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-{{ $color }}-600 dark:text-{{ $color }}-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white truncate max-w-xs">{{ $doc->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $doc->property?->title ?? 'Général' }} · {{ number_format($doc->file_size / 1024, 1) }} Ko</p>
                    </div>
                </div>
                <a href="{{ route('owner.documents.download', $doc) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 ml-4 shrink-0">
                    <i data-lucide="download" class="w-4 h-4"></i>
                </a>
            </div>
        @empty
            <div class="text-center py-6">
                <i data-lucide="folder-open" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                <p class="text-sm text-gray-400 dark:text-gray-500">Aucun document récent</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    const textColor = isDark ? '#9ca3af' : '#6b7280';

    // Monthly Revenue & Expenses Chart
    const ctx1 = document.getElementById('dashboardChart').getContext('2d');
    new Chart(ctx1, {
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

    // Collection Rate Trend
    const ctx2 = document.getElementById('collectionChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: @json(array_column($collectionTrend, 'label')),
            datasets: [{
                label: 'Taux de collecte',
                data: @json(array_column($collectionTrend, 'rate')),
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#0d9488',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: { color: textColor, callback: v => v + '%' },
                    grid: { color: gridColor }
                },
                x: { ticks: { color: textColor }, grid: { display: false } }
            }
        }
    });
</script>
@endpush