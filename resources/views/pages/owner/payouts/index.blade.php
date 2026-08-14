@extends('layouts.owner')

@section('title', 'Virements Mobile Money')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Virements Mobile Money</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Historique de vos virements pawaPay (payouts).</p>
    </div>
    <a href="{{ route('owner.payouts.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-lg text-sm font-medium transition shrink-0">
        <i data-lucide="send" class="w-4 h-4"></i>
        Nouveau virement
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-success/10 border border-success/20 rounded-xl text-sm text-success flex items-start gap-2">
        <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('warning'))
    <div class="mb-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl text-sm text-amber-700 dark:text-amber-300 flex items-start gap-2">
        <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5 shrink-0"></i>
        {{ session('warning') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-700 dark:text-red-300 flex items-start gap-2">
        <i data-lucide="x-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
        {{ session('error') }}
    </div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase bg-gray-50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium">N° pawaPay</th>
                    <th class="px-5 py-3 font-medium">Opérateur</th>
                    <th class="px-5 py-3 font-medium text-right">Montant (FCFA)</th>
                    <th class="px-5 py-3 font-medium text-center">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($payouts as $payout)
                    @php
                        $statusColors = [
                            'pending'    => 'amber',
                            'accepted'   => 'blue',
                            'processing' => 'blue',
                            'completed'  => 'success',
                            'failed'     => 'red',
                            'rejected'   => 'red',
                        ];
                        $statusLabels = [
                            'pending'    => 'En attente',
                            'accepted'   => 'Accepté',
                            'processing' => 'En cours',
                            'completed'  => 'Complété',
                            'failed'     => 'Échoué',
                            'rejected'   => 'Refusé',
                        ];
                        $sc = $statusColors[$payout->status] ?? 'gray';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                            {{ $payout->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">
                            {{ $payout->payout_id ? Str::limit($payout->payout_id, 18) : '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-700 dark:text-gray-300">
                            {{ $payout->provider ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-800 dark:text-white">
                            {{ number_format($payout->amount, 0, ',', ' ') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($sc === 'success')
                                <span class="text-xs px-2 py-1 rounded-full bg-success/10 text-success">
                                    {{ $statusLabels[$payout->status] ?? $payout->status }}
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-{{ $sc }}-100 dark:bg-{{ $sc }}-900/30 text-{{ $sc }}-600 dark:text-{{ $sc }}-400">
                                    {{ $statusLabels[$payout->status] ?? $payout->status }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                <i data-lucide="send" class="w-10 h-10 opacity-30"></i>
                                <p class="text-sm">Aucun virement effectué pour l'instant.</p>
                                <a href="{{ route('owner.payouts.create') }}" class="text-primary hover:underline text-sm font-medium">
                                    Effectuer votre premier virement
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payouts->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $payouts->links() }}
        </div>
    @endif
</div>
@endsection
