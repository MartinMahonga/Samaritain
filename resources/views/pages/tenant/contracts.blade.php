@extends('layouts.tenant')

@section('title', 'Mes contrats')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mes contrats</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Gérez vos contrats de bail</p>
</div>

<div class="space-y-4">
    @forelse($contracts as $contract)
        @php
            $sc = ['draft' => 'gray', 'pending_owner' => 'amber', 'pending_tenant' => 'orange', 'active' => 'emerald', 'rejected' => 'red', 'cancelled' => 'red', 'terminated' => 'red'];
            $sl = ['draft' => 'Brouillon', 'pending_owner' => 'En attente propriétaire', 'pending_tenant' => 'En attente locataire', 'active' => 'Actif', 'rejected' => 'Refusé', 'cancelled' => 'Annulé', 'terminated' => 'Résilié'];
            $c = $sc[$contract->status] ?? 'gray';
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="font-semibold text-gray-800 dark:text-white">{{ $contract->property->title }}</h3>
                        <span class="text-xs px-2 py-1 rounded-full bg-{{ $c }}-100 dark:bg-{{ $c }}-900/30 text-{{ $c }}-600 dark:text-{{ $c }}-400">
                            {{ $sl[$contract->status] ?? $contract->status }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                        <p>📍 {{ $contract->property->address }}</p>
                        <p>📅 Du {{ $contract->start_date->format('d/m/Y') }} au {{ $contract->end_date?->format('d/m/Y') ?? 'Illimité' }}</p>
                        <p>💰 {{ number_format($contract->monthly_rent, 0, ',', ' ') }} FCFA/mois</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('tenant.contracts.show', $contract) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Consulter
                    </a>
                    @if($contract->status === 'pending_tenant')
                        <form action="{{ route('tenant.contracts.sign', $contract) }}" method="POST" class="inline" onsubmit="return confirm('Vous êtes sur le point de signer ce contrat. Confirmez-vous ?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H2v-4.572L16.732 3.732z"></path></svg>
                                Signer
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('tenant.contracts.pdf', $contract) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">Aucun contrat enregistré</p>
        </div>
    @endforelse
</div>
@endsection
</parameter>
</parameter>
</write_to_file>