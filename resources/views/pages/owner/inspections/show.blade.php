@extends('layouts.owner')

@section('title', 'État des lieux — ' . $inspection->property->title)

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.inspections.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux états des lieux
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ $inspection->type === 'check_in' ? '↓ Entrée' : '↑ Sortie' }} — {{ $inspection->property->title }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $inspection->date->format('d/m/Y') }} &bull; Inspecteur: {{ $inspection->inspector_name }}</p>
        </div>
        <a href="{{ route('owner.inspections.pdf', $inspection) }}"
            class="flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition shrink-0">
            <i data-lucide="download" class="w-4 h-4"></i> Télécharger PDF
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Room-by-Room --}}
    <div class="lg:col-span-2 space-y-4">
        @php
            $statusLabels = ['good' => 'Bon état', 'average' => 'État moyen', 'damaged' => 'Endommagé', 'new' => 'Neuf'];
            $statusColors = ['good' => 'emerald', 'average' => 'amber', 'damaged' => 'red', 'new' => 'blue'];
        @endphp
        @foreach(($inspection->rooms_data ?? []) as $room)
            @php
                $roomColor = $statusColors[$room['status'] ?? 'good'] ?? 'gray';
                $roomLabel = $statusLabels[$room['status'] ?? 'good'] ?? ucfirst($room['status']);
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $room['name'] ?? 'Pièce' }}</h3>
                    <span class="text-xs px-2 py-1 rounded-full bg-{{ $roomColor }}-100 dark:bg-{{ $roomColor }}-900/30 text-{{ $roomColor }}-600 dark:text-{{ $roomColor }}-400">
                        {{ $roomLabel }}
                    </span>
                </div>
                @if(!empty($room['notes']))
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $room['notes'] }}</p>
                @endif
            </div>
        @endforeach

        @if($inspection->notes)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Observations générales</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $inspection->notes }}</p>
            </div>
        @endif

        @if($inspection->photos && count($inspection->photos) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Photos</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($inspection->photos as $photo)
                        <a href="{{ Storage::url($photo) }}" target="_blank">
                            <img src="{{ Storage::url($photo) }}" class="rounded-lg w-full h-32 object-cover hover:opacity-80 transition" alt="Photo état des lieux">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Informations</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Bien</dt>
                    <dd class="text-gray-800 dark:text-white font-medium text-right truncate max-w-xs">{{ $inspection->property->title }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">Type</dt>
                    <dd>
                        <span class="text-xs px-2 py-1 rounded-full {{ $inspection->type === 'check_in' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' }}">
                            {{ $inspection->type === 'check_in' ? 'Entrée' : 'Sortie' }}
                        </span>
                    </dd>
                </div>
                @if($inspection->contract)
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Locataire</dt>
                        <dd class="text-gray-800 dark:text-white">{{ $inspection->contract->tenant_name }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Signatures</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Propriétaire</p>
                    <p class="font-medium text-gray-800 dark:text-white">{{ $inspection->owner_signature ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Locataire</p>
                    <p class="font-medium text-gray-800 dark:text-white">{{ $inspection->tenant_signature ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
