@extends('layouts.owner')

@section('title', 'Contrats de bail')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Contrats de bail</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez vos contrats de location.</p>
    </div>
    <a href="{{ route('owner.contracts.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition shrink-0">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Nouveau contrat
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                    <th class="px-5 py-3 font-medium">Locataire</th>
                    <th class="px-5 py-3 font-medium">Propriété</th>
                    <th class="px-5 py-3 font-medium text-right">Loyer / mois</th>
                    <th class="px-5 py-3 font-medium">Début</th>
                    <th class="px-5 py-3 font-medium">Fin</th>
                    <th class="px-5 py-3 font-medium text-center">Statut</th>
                    <th class="px-5 py-3 font-medium text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($contracts as $contract)
                    @php
                        $statusColors = [
                            'draft' => 'gray',
                            'pending_owner' => 'amber',
                            'pending_tenant' => 'orange',
                            'active' => 'emerald',
                            'rejected' => 'red',
                            'cancelled' => 'red',
                            'terminated' => 'red',
                        ];
                        $statusLabels = [
                            'draft' => 'Brouillon',
                            'pending_owner' => 'En attente propriétaire',
                            'pending_tenant' => 'En attente locataire',
                            'active' => 'Actif',
                            'rejected' => 'Refusé',
                            'cancelled' => 'Annulé',
                            'terminated' => 'Résilié',
                        ];
                        $color = $statusColors[$contract->status] ?? 'gray';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800 dark:text-white">{{ $contract->tenant_name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $contract->tenant_phone }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $contract->property->title }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">
                            {{ number_format($contract->monthly_rent, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $contract->start_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $contract->end_date?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-600 dark:text-{{ $color }}-400">
                                {{ $statusLabels[$contract->status] ?? $contract->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('owner.contracts.show', $contract) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Voir</a>
                                @if($contract->canBeDeleted())
                                    <form action="{{ route('owner.contracts.destroy', $contract) }}" method="POST" onsubmit="return confirm('⚠️ Supprimer définitivement ce contrat et toutes les données associées ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <i data-lucide="file-text" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Aucun contrat enregistré</p>
                            <a href="{{ route('owner.contracts.create') }}" class="mt-2 inline-block text-xs text-primary hover:underline">Créer un contrat →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contracts->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            {{ $contracts->links() }}
        </div>
    @endif
</div>
@endsection