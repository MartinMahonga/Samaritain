@extends('layouts.owner')

@section('title', 'Comparer les états des lieux')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.inspections.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux états des lieux
    </a>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Comparer les états des lieux</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Comparez l'état d'entrée et de sortie d'un bien.</p>
</div>

{{-- Property Selector --}}
<form method="GET" action="{{ route('owner.inspections.compare') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
    <div class="flex gap-3">
        <select name="property_id" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Sélectionnez un bien</option>
            @foreach($properties as $p)
                <option value="{{ $p->id }}" @selected(($property->id ?? null) == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition shrink-0">Comparer</button>
    </div>
</form>

@if(isset($property) && $checkIn && $checkOut)
    {{-- Summary header --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider">Bien</p>
            <p class="text-lg font-semibold text-gray-800 dark:text-white mt-1">{{ $property->title }}</p>
        </div>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-4">
            <p class="text-xs text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">État d'entrée</p>
            <p class="text-lg font-semibold text-emerald-700 dark:text-emerald-300 mt-1">{{ $checkIn->date->format('d/m/Y') }}</p>
            <p class="text-xs text-emerald-500 dark:text-emerald-400">Par {{ $checkIn->inspector_name }}</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800 p-4">
            <p class="text-xs text-red-600 dark:text-red-400 uppercase tracking-wider">État de sortie</p>
            <p class="text-lg font-semibold text-red-700 dark:text-red-300 mt-1">{{ $checkOut->date->format('d/m/Y') }}</p>
            <p class="text-xs text-red-500 dark:text-red-400">Par {{ $checkOut->inspector_name }}</p>
        </div>
    </div>

    {{-- Rooms comparison --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-800 dark:text-white">Comparaison par pièce</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase bg-gray-50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-5 py-3 font-medium">Pièce</th>
                        <th class="px-5 py-3 font-medium">Élément</th>
                        <th class="px-5 py-3 font-medium bg-emerald-50 dark:bg-emerald-900/10">État entrée</th>
                        <th class="px-5 py-3 font-medium bg-red-50 dark:bg-red-900/10">État sortie</th>
                        <th class="px-5 py-3 font-medium text-center">Différence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @php
                        $checkInRooms = $checkIn->rooms_data ?? [];
                        $checkOutRooms = $checkOut->rooms_data ?? [];
                        $allRooms = collect(array_merge(
                            array_keys($checkInRooms),
                            array_keys($checkOutRooms)
                        ))->unique()->values();
                    @endphp

                    @forelse($allRooms as $roomName)
                        @php
                            $inRoom = $checkInRooms[$roomName] ?? [];
                            $outRoom = $checkOutRooms[$roomName] ?? [];
                            $allElements = collect(array_merge(
                                array_keys($inRoom),
                                array_keys($outRoom)
                            ))->unique()->values();
                        @endphp
                        @foreach($allElements as $elementIndex => $elementName)
                            @php
                                $inStatus = $inRoom[$elementName] ?? null;
                                $outStatus = $outRoom[$elementName] ?? null;
                                $different = $inStatus !== $outStatus;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                @if($elementIndex === 0)
                                    <td class="px-5 py-3 font-medium text-gray-800 dark:text-white" rowspan="{{ $allElements->count() }}">
                                        {{ $roomName }}
                                    </td>
                                @endif
                                <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $elementName }}</td>
                                <td class="px-5 py-3 bg-emerald-50/50 dark:bg-emerald-900/5">
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full
                                        {{ $inStatus === 'clean' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                                        {{ $inStatus === 'good' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                                        {{ $inStatus === 'fair' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                                        {{ $inStatus === 'damaged' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}
                                        {{ !$inStatus ? 'bg-gray-100 dark:bg-gray-700 text-gray-400' : '' }}">
                                        {{ $inStatus ? ucfirst($inStatus) : '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 bg-red-50/50 dark:bg-red-900/5">
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full
                                        {{ $outStatus === 'clean' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                                        {{ $outStatus === 'good' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                                        {{ $outStatus === 'fair' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                                        {{ $outStatus === 'damaged' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}
                                        {{ !$outStatus ? 'bg-gray-100 dark:bg-gray-700 text-gray-400' : '' }}">
                                        {{ $outStatus ? ucfirst($outStatus) : '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($different)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                                            Dégradation
                                        </span>
                                    @elseif($inStatus && $outStatus)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                            Identique
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
                                Aucune donnée de pièces disponible pour ces états des lieux.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Notes comparison --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
        @if($checkIn->notes)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4 text-emerald-500"></i>
                    Notes d'entrée
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $checkIn->notes }}</p>
            </div>
        @endif
        @if($checkOut->notes)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4 text-red-500"></i>
                    Notes de sortie
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $checkOut->notes }}</p>
            </div>
        @endif
    </div>

@elseif(isset($property))
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
        <i data-lucide="search-x" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
        <p class="text-sm text-gray-400 dark:text-gray-500">
            Aucun état des lieux d'entrée et/ou de sortie trouvé pour ce bien.
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            Créez d'abord un état d'entrée et un état de sortie pour pouvoir les comparer.
        </p>
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
        <i data-lucide="git-compare" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
        <p class="text-sm text-gray-400 dark:text-gray-500">Sélectionnez un bien pour comparer ses états des lieux.</p>
    </div>
@endif
@endsection