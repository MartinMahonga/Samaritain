@extends('layouts.base')

@section('title', 'Mes favoris')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    <div class="mb-8">
        <h1 class="text-xl font-medium text-gray-900 dark:text-white">Mes favoris</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Retrouvez tous les biens et parcelles que vous avez enregistrés.</p>
    </div>

    {{-- Section Biens Immobiliers --}}
    <div class="mb-10">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Biens immobiliers</h2>
        @if ($favoritesProperties->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                <i data-lucide="heart-off" class="w-9 h-9 mx-auto text-gray-300 dark:text-gray-600"></i>
                <h2 class="mt-3 text-base font-medium text-gray-700 dark:text-gray-300">Aucun favori</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Vous n'avez encore ajouté aucun bien à vos favoris.</p>
                <a href="{{ route('index') }}"
                    class="inline-flex items-center gap-2 mt-5 px-4 py-2 bg-primary dark:bg-primary-600 text-white text-sm rounded-lg hover:opacity-90 dark:hover:bg-primary-700 transition">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Découvrir les biens
                </a>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($favoritesProperties as $property)
                    <a href="{{ route('property.show', $property) }}"
                        class="group flex flex-col sm:flex-row bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition overflow-hidden
                               h-auto sm:h-[130px]">

                        {{-- Image --}}
                        <div class="w-full h-32 sm:w-40 sm:h-full flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                            <img
                                src="{{ $property->images->first()?->image_url }}"
                                alt="{{ $property->title }}"
                                class="w-full h-full object-cover">
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 p-3 sm:px-4 flex flex-col justify-between min-w-0 overflow-hidden">

                            {{-- Haut : titre + prix --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h2 class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-primary dark:group-hover:text-primary-400 transition">
                                        {{ $property->title }}
                                    </h2>
                                    <p class="mt-0.5 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        {{ $property->city->name }}
                                    </p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ number_format($property->price, 0, ',', ' ') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500">FCFA / mois</p>
                                </div>
                            </div>

                            {{-- Description (masquée sur mobile) --}}
                            <p class="hidden sm:block text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-1">
                                {{ $property->description }}
                            </p>

                            {{-- Bas : badges + lien --}}
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-[10px] text-gray-600 dark:text-gray-300">
                                        <i data-lucide="bed" class="w-3 h-3"></i>
                                        {{ $property->bedrooms }} ch.
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-[10px] text-gray-600 dark:text-gray-300">
                                        <i data-lucide="bath" class="w-3 h-3"></i>
                                        {{ $property->bathrooms }} sdb
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-[10px] text-gray-600 dark:text-gray-300">
                                        <i data-lucide="ruler" class="w-3 h-3"></i>
                                        {{ $property->surface }} m²
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded text-[10px]">
                                        <i data-lucide="badge-check" class="w-3 h-3"></i>
                                        {{ $property->status }}
                                    </span>
                                </div>
                                <span class="flex items-center gap-1 text-[11px] text-primary dark:text-primary-400 font-medium flex-shrink-0">
                                    Voir <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </span>
                            </div>

                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6 text-gray-600 dark:text-gray-400">
                {{ $favoritesProperties->appends(request()->except('properties_page'))->links() }}
            </div>
        @endif
    </div>

    {{-- Section Parcelles --}}
    <div class="mt-12">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Parcelles favorites</h2>
        @if ($favoritesParcels->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                <i data-lucide="heart-off" class="w-9 h-9 mx-auto text-gray-300 dark:text-gray-600"></i>
                <h2 class="mt-3 text-base font-medium text-gray-700 dark:text-gray-300">Aucune parcelle</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Vous n'avez encore ajouté aucune parcelle à vos favoris.</p>
                <a href="{{ route('parcelles.index') }}"
                    class="inline-flex items-center gap-2 mt-5 px-4 py-2 bg-primary dark:bg-primary-600 text-white text-sm rounded-lg hover:opacity-90 dark:hover:bg-primary-700 transition">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Découvrir les parcelles
                </a>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($favoritesParcels as $parcel)
                    <a href="{{ route('parcelles.show', $parcel) }}"
                        class="group flex flex-col sm:flex-row bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition overflow-hidden
                               h-auto sm:h-[130px]">

                        {{-- Image --}}
                        <div class="w-full h-32 sm:w-40 sm:h-full flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                            @php
                                $mainImage = $parcel->images->firstWhere('principale', true) ?? $parcel->images->first();
                            @endphp
                            <img
                                src="{{ $mainImage?->url }}"
                                alt="{{ $parcel->titre }}"
                                class="w-full h-full object-cover"
                                onerror="this.src='/images/placeholder.png'">
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 p-3 sm:px-4 flex flex-col justify-between min-w-0 overflow-hidden">

                            {{-- Haut : titre + prix --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h2 class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-primary dark:group-hover:text-primary-400 transition">
                                        {{ $parcel->titre }}
                                    </h2>
                                    <p class="mt-0.5 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        {{ $parcel->quartier }}, {{ $parcel->ville }}
                                    </p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ number_format($parcel->prix, 0, ',', ' ') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500">FCFA</p>
                                </div>
                            </div>

                            {{-- Description (masquée sur mobile) --}}
                            <p class="hidden sm:block text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-1">
                                {{ $parcel->description }}
                            </p>

                            {{-- Bas : badges + lien --}}
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-[10px] text-gray-600 dark:text-gray-300">
                                        <i data-lucide="ruler" class="w-3 h-3"></i>
                                        {{ number_format($parcel->superficie, 0, ',', ' ') }} m²
                                    </span>
                                    @if ($parcel->viabilisee)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-[10px]">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            Viabilisée
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded text-[10px]">
                                        <i data-lucide="badge-check" class="w-3 h-3"></i>
                                        {{ $parcel->statut }}
                                    </span>
                                </div>
                                <span class="flex items-center gap-1 text-[11px] text-primary dark:text-primary-400 font-medium flex-shrink-0">
                                    Voir <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </span>
                            </div>

                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6 text-gray-600 dark:text-gray-400">
                {{ $favoritesParcels->appends(request()->except('parcels_page'))->links() }}
            </div>
        @endif
    </div>

</div>
@endsection