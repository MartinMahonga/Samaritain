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
            <div class="space-y-3">
                @foreach ($favoritesProperties as $property)
                    <div x-data="{ removing: false }"
                         x-show="!removing"
                         x-transition.duration.200ms
                         class="group relative flex bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden p-2 gap-3">

                        {{-- Image --}}
                        <a href="{{ route('property.show', $property) }}" class="w-24 h-24 sm:w-28 sm:h-28 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 relative block">
                            <img
                                src="{{ $property->images->first()?->image_url }}"
                                alt="{{ $property->title }}"
                                class="w-full h-full object-cover">
                        </a>

                        {{-- Contenu --}}
                        <a href="{{ route('property.show', $property) }}" class="flex-1 min-w-0 flex flex-col justify-center gap-1.5 py-1 pr-10">

                            <h2 class="text-[15px] font-semibold text-gray-900 dark:text-white truncate group-hover:text-primary dark:group-hover:text-primary-400 transition">
                                {{ $property->title }}
                            </h2>

                            <p class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                <span class="truncate">{{ $property->city->name }}</span>
                            </p>

                            <div class="flex items-center justify-between gap-2 mt-0.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full text-[10px] font-medium text-gray-600 dark:text-gray-300">
                                        <i data-lucide="bed" class="w-3 h-3"></i>{{ $property->bedrooms }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full text-[10px] font-medium text-gray-600 dark:text-gray-300">
                                        <i data-lucide="bath" class="w-3 h-3"></i>{{ $property->bathrooms }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full text-[10px] font-medium text-gray-600 dark:text-gray-300">
                                        <i data-lucide="ruler" class="w-3 h-3"></i>{{ $property->surface }} m²
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ number_format($property->price, 0, ',', ' ') }} <span class="text-[10px] font-normal text-gray-400 dark:text-gray-500">FCFA/mois</span>
                                </p>
                            </div>
                        </a>

                        {{-- Bouton favori (cœur) --}}
                        <form action="{{ route('property.favorite.destroy', $property) }}" method="POST"
                              @submit.prevent="removing = true; $nextTick(() => $el.submit())"
                              class="absolute top-3 right-3 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-100 dark:hover:bg-red-500/20 hover:scale-110 transition"
                                title="Retirer des favoris">
                                <i data-lucide="heart" class="w-4 h-4 fill-current"></i>
                            </button>
                        </form>

                    </div>
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
            <div class="space-y-3">
                @foreach ($favoritesParcels as $parcel)
                    @php
                        $mainImage = $parcel->images->firstWhere('principale', true) ?? $parcel->images->first();
                    @endphp
                    <div x-data="{ removing: false }"
                         x-show="!removing"
                         x-transition.duration.200ms
                         class="group relative flex bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden p-2 gap-3">

                        {{-- Image --}}
                        <a href="{{ route('parcelles.show', $parcel) }}" class="w-24 h-24 sm:w-28 sm:h-28 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 relative block">
                            <img
                                src="{{ $mainImage?->url }}"
                                alt="{{ $parcel->titre }}"
                                class="w-full h-full object-cover"
                                onerror="this.src='/images/placeholder.png'">
                        </a>

                        {{-- Contenu --}}
                        <a href="{{ route('parcelles.show', $parcel) }}" class="flex-1 min-w-0 flex flex-col justify-center gap-1.5 py-1 pr-10">

                            <h2 class="text-[15px] font-semibold text-gray-900 dark:text-white truncate group-hover:text-primary dark:group-hover:text-primary-400 transition">
                                {{ $parcel->titre }}
                            </h2>

                            <p class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                <span class="truncate">{{ $parcel->quartier }}, {{ $parcel->ville }}</span>
                            </p>

                            <div class="flex items-center justify-between gap-2 mt-0.5">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full text-[10px] font-medium text-gray-600 dark:text-gray-300">
                                        <i data-lucide="ruler" class="w-3 h-3"></i>{{ number_format($parcel->superficie, 0, ',', ' ') }} m²
                                    </span>
                                    @if ($parcel->viabilisee)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-[10px] font-medium">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>Viabilisée
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-[10px] font-medium">
                                        {{ $parcel->statut }}
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ number_format($parcel->prix, 0, ',', ' ') }} <span class="text-[10px] font-normal text-gray-400 dark:text-gray-500">FCFA</span>
                                </p>
                            </div>
                        </a>

                        {{-- Bouton favori (cœur) --}}
                        <form action="{{ route('parcel.favorite.destroy', $parcel) }}" method="POST"
                              @submit.prevent="removing = true; $nextTick(() => $el.submit())"
                              class="absolute top-3 right-3 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 hover:bg-red-100 dark:hover:bg-red-500/20 hover:scale-110 transition"
                                title="Retirer des favoris">
                                <i data-lucide="heart" class="w-4 h-4 fill-current"></i>
                            </button>
                        </form>

                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-gray-600 dark:text-gray-400">
                {{ $favoritesParcels->appends(request()->except('parcels_page'))->links() }}
            </div>
        @endif
    </div>

</div>
@endsection