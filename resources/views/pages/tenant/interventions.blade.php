@extends('layouts.tenant')

@section('title', 'Interventions')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Interventions</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Suivi des demandes d'intervention.</p>
</div>

@if($contract)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/30 border-b dark:border-gray-700">
                        <th class="px-5 py-3">Titre</th>
                        <th class="px-5 py-3">Type</th>
                        <th class="px-5 py-3">Catégorie</th>
                        <th class="px-5 py-3">Priorité</th>
                        <th class="px-5 py-3">Statut</th>
                        <th class="px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($interventions as $intervention)
                        @php
                            $uc = ['low' => 'gray', 'medium' => 'amber', 'high' => 'orange', 'emergency' => 'red'];
                            $sc = ['pending' => 'gray', 'approved' => 'blue', 'in_progress' => 'amber', 'completed' => 'emerald', 'cancelled' => 'red'];
                            $sl = ['pending' => 'En attente', 'approved' => 'Approuvée', 'in_progress' => 'En cours', 'completed' => 'Terminée', 'cancelled' => 'Annulée'];
                            $ul = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'emergency' => 'Urgente'];
                            $u = $uc[$intervention->urgency] ?? 'gray';
                            $s = $sc[$intervention->status] ?? 'gray';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">{{ $intervention->title }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ ucfirst($intervention->type) }}</td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ ucfirst($intervention->category) }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-{{ $u }}-100 text-{{ $u }}-600">{{ $ul[$intervention->urgency] ?? $intervention->urgency }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-{{ $s }}-100 text-{{ $s }}-600">{{ $sl[$intervention->status] ?? $intervention->status }}</span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-500">{{ $intervention->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400">Aucune intervention enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($interventions->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $interventions->links() }}</div>
        @endif
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
        <i data-lucide="wrench" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
        <p class="text-gray-400 dark:text-gray-500">Aucun contrat actif trouvé.</p>
    </div>
@endif
@endsection