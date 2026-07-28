@extends('layouts.tenant')

@section('title', 'Mes Contrats')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mes Contrats</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Historique de vos contrats de location.</p>
</div>

<div class="space-y-4">
    @forelse($contracts as $contract)
        @php
            $sc = ['active' => 'emerald', 'pending' => 'amber', 'terminated' => 'red'];
            $sl = ['active' => 'Actif', 'pending' => 'En attente', 'terminated' => 'Résilié'];
            $c = $sc[$contract->status] ?? 'gray';
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs px-2 py-1 rounded-full bg-{{ $c }}-100 dark:bg-{{ $c }}-900/30 text-{{ $c }}-600 dark:text-{{ $c }}-400">
                            {{ $sl[$contract->status] ?? $contract->status }}
                        </span>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $contract->property->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $contract->property->address }}</p>
                    <div class="flex gap-4 mt-2 text-xs text-gray-400 dark:text-gray-500">
                        <span>Du {{ $contract->start_date->format('d/m/Y') }}</span>
                        <span>Au {{ $contract->end_date?->format('d/m/Y') ?? '—' }}</span>
                        <span class="font-medium text-gray-800 dark:text-white">{{ number_format($contract->monthly_rent, 0, ',', ' ') }} FCFA/mois</span>
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 shrink-0">
                    Locataire : <span class="font-medium text-gray-800 dark:text-white">{{ $contract->tenant_name }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <i data-lucide="file-text" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
            <p class="text-gray-400 dark:text-gray-500">Aucun contrat trouvé.</p>
        </div>
    @endforelse
</div>
@endsection