@extends('layouts.owner')

@section('title', $intervention->title)

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.interventions.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux interventions
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $intervention->title }}</h1>
    </div>
</div>

@php
    $urgencyColors = ['low' => 'gray', 'medium' => 'amber', 'high' => 'orange', 'emergency' => 'red'];
    $statusColors = ['pending' => 'gray', 'approved' => 'blue', 'in_progress' => 'amber', 'completed' => 'emerald', 'cancelled' => 'red'];
    $statusLabels = ['pending' => 'En attente', 'approved' => 'Approuvé', 'in_progress' => 'En cours', 'completed' => 'Terminé', 'cancelled' => 'Annulé'];
    $uc = $urgencyColors[$intervention->urgency] ?? 'gray';
    $sc = $statusColors[$intervention->status] ?? 'gray';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Details --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="text-xs px-2 py-1 rounded-full bg-{{ $uc }}-100 dark:bg-{{ $uc }}-900/30 text-{{ $uc }}-600 dark:text-{{ $uc }}-400">
                    Urgence: {{ ucfirst($intervention->urgency) }}
                </span>
                <span class="text-xs px-2 py-1 rounded-full bg-{{ $sc }}-100 dark:bg-{{ $sc }}-900/30 text-{{ $sc }}-600 dark:text-{{ $sc }}-400">
                    {{ $statusLabels[$intervention->status] ?? $intervention->status }}
                </span>
                @if($intervention->is_renovation)
                    <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">Rénovation</span>
                @endif
            </div>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $intervention->description }}</p>
        </div>

        {{-- Photos --}}
        @if($intervention->photos && count($intervention->photos) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Photos</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($intervention->photos as $photo)
                        <a href="{{ Storage::url($photo) }}" target="_blank">
                            <img src="{{ Storage::url($photo) }}" class="rounded-lg w-full h-32 object-cover hover:opacity-80 transition" alt="Photo">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Update Status --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Mettre à jour le statut</h3>
            <form action="{{ route('owner.interventions.update-status', $intervention) }}" method="POST">
                @csrf
                <div class="flex flex-wrap gap-3">
                    <select name="status" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        @foreach(['pending' => 'En attente', 'approved' => 'Approuvé', 'in_progress' => 'En cours', 'completed' => 'Terminé', 'cancelled' => 'Annulé'] as $val => $label)
                            <option value="{{ $val }}" @selected($intervention->status === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="cost" value="{{ $intervention->cost }}" placeholder="Coût final (FCFA)"
                        class="w-40 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 transition">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Informations</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Bien</dt>
                    <dd class="text-gray-800 dark:text-white font-medium text-right max-w-xs truncate">{{ $intervention->property->title }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Catégorie</dt>
                    <dd class="text-gray-800 dark:text-white">{{ ucfirst($intervention->category) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Coût</dt>
                    <dd class="text-gray-800 dark:text-white font-bold">{{ number_format($intervention->cost, 0, ',', ' ') }} FCFA</dd>
                </div>
                @if($intervention->scheduled_at)
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Planifiée le</dt>
                        <dd class="text-gray-800 dark:text-white">{{ $intervention->scheduled_at->format('d/m/Y H:i') }}</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Créée le</dt>
                    <dd class="text-gray-400 dark:text-gray-500">{{ $intervention->created_at->format('d/m/Y') }}</dd>
                </div>
            </dl>
        </div>

        @if($intervention->artisan)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Artisan assigné</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                        <i data-lucide="hard-hat" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $intervention->artisan->business_name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $intervention->artisan->profession }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
