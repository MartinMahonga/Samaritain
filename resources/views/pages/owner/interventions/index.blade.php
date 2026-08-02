@extends('layouts.owner')

@section('title', 'Maintenance & Travaux')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Maintenance & Travaux</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">
            {{ $pending }} intervention(s) en cours &bull; {{ number_format($totalCost, 0, ',', ' ') }} FCFA dépensés
        </p>
    </div>
    <a href="{{ route('owner.interventions.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition shrink-0">
        <i data-lucide="plus" class="w-4 h-4"></i> Nouvelle intervention
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('owner.interventions.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-5">
    <div class="flex flex-wrap gap-3">
        <select name="property_id" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous les biens</option>
            @foreach($properties as $p)
                <option value="{{ $p->id }}" @selected(request('property_id') == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous statuts</option>
            <option value="pending" @selected(request('status') === 'pending')>En attente</option>
            <option value="in_progress" @selected(request('status') === 'in_progress')>En cours</option>
            <option value="completed" @selected(request('status') === 'completed')>Terminé</option>
            <option value="cancelled" @selected(request('status') === 'cancelled')>Annulé</option>
        </select>
        <select name="urgency" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Toutes urgences</option>
            <option value="emergency" @selected(request('urgency') === 'emergency')>🚨 Urgence</option>
            <option value="high" @selected(request('urgency') === 'high')>🔴 Haute</option>
            <option value="medium" @selected(request('urgency') === 'medium')>🟡 Moyenne</option>
            <option value="low" @selected(request('urgency') === 'low')>🟢 Faible</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 transition">Filtrer</button>
    </div>
</form>

<div class="space-y-3">
    @forelse($interventions as $intervention)
        @php
            $urgencyColors = ['low' => 'gray', 'medium' => 'amber', 'high' => 'orange', 'emergency' => 'red'];
            $statusColors = ['pending' => 'gray', 'approved' => 'blue', 'in_progress' => 'amber', 'completed' => 'emerald', 'cancelled' => 'red'];
            $statusLabels = ['pending' => 'En attente', 'approved' => 'Approuvé', 'in_progress' => 'En cours', 'completed' => 'Terminé', 'cancelled' => 'Annulé'];
            $urgencyLabels = ['low' => 'Faible', 'medium' => 'Moyenne', 'high' => 'Haute', 'emergency' => 'Urgence'];
            $uc = $urgencyColors[$intervention->urgency] ?? 'gray';
            $sc = $statusColors[$intervention->status] ?? 'gray';
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:shadow-md transition">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 bg-{{ $uc }}-100 dark:bg-{{ $uc }}-900/30 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="wrench" class="w-5 h-5 text-{{ $uc }}-600 dark:text-{{ $uc }}-400"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-800 dark:text-white truncate">{{ $intervention->title }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $intervention->property->title }} &bull; {{ ucfirst($intervention->category) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <span class="text-xs px-2 py-1 rounded-full bg-{{ $uc }}-100 dark:bg-{{ $uc }}-900/30 text-{{ $uc }}-600 dark:text-{{ $uc }}-400">
                    {{ $urgencyLabels[$intervention->urgency] ?? $intervention->urgency }}
                </span>
                <span class="text-xs px-2 py-1 rounded-full bg-{{ $sc }}-100 dark:bg-{{ $sc }}-900/30 text-{{ $sc }}-600 dark:text-{{ $sc }}-400">
                    {{ $statusLabels[$intervention->status] ?? $intervention->status }}
                </span>
                @if($intervention->cost > 0)
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ number_format($intervention->cost, 0, ',', ' ') }} FCFA</span>
                @endif
                <a href="{{ route('owner.interventions.show', $intervention) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs whitespace-nowrap">Détails →</a>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
            <i data-lucide="check-circle" class="w-12 h-12 text-emerald-400 dark:text-emerald-500 mx-auto mb-3"></i>
            <p class="text-sm text-gray-400 dark:text-gray-500">Aucune intervention enregistrée</p>
            <a href="{{ route('owner.interventions.create') }}" class="mt-2 inline-block text-xs text-primary hover:underline">Signaler une intervention →</a>
        </div>
    @endforelse
</div>

@if($interventions->hasPages())
    <div class="mt-4">{{ $interventions->links() }}</div>
@endif
@endsection
