@extends('layouts.base')

@section('title', 'Acheter un pass visite')

@section('content')
<div class="font-body bg-background dark:bg-gray-950 text-[#0F0E0C] dark:text-white antialiased min-h-screen">
    <div class="max-w-4xl mx-auto px-6 py-10 pb-20">

        {{-- Breadcrumb --}}
        <nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-xs text-[#6B6660] dark:text-gray-400 mb-10 font-body">
            <a href="{{ route('index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Accueil</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            @if($visitPassable instanceof \App\Models\Property)
                <a href="{{ route('property.index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Propriétés</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="{{ route('property.show', $visitPassable) }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">{{ $visitPassable->title }}</a>
            @else
                <a href="{{ route('parcelles.index') }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">Parcelles</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="{{ route('parcelles.show', $visitPassable) }}" class="hover:text-primary dark:hover:text-primary-400 transition-colors">{{ $visitPassable->titre }}</a>
            @endif
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="dark:text-gray-300">Pass visite</span>
        </nav>

        <h1 class="font-display font-semibold text-3xl mb-8">Acheter un pass visite</h1>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-8 items-start">

            {{-- Form --}}
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="font-display text-lg font-semibold mb-6 dark:text-white">Vos informations</h2>

                    <form action="{{ route('my-visit-passes.store') }}" method="POST" class="space-y-5">
                        @csrf
                        @if($visitPassable instanceof \App\Models\Property)
                            <input type="hidden" name="property_id" value="{{ $visitPassable->id }}">
                        @else
                            <input type="hidden" name="parcelle_id" value="{{ $visitPassable->id }}">
                        @endif

                        <x-form.input
                            label="Nom complet"
                            name="holder_name"
                            icon="user"
                            placeholder="Jean Dupont"
                            value="{{ old('holder_name', auth()->user()->name) }}"
                            required
                        />

                        <x-form.input
                            label="Téléphone"
                            name="phone"
                            icon="phone"
                            placeholder="06 800 71 38"
                            value="{{ old('phone') }}"
                            required
                        />

                        <x-form.input
                            label="Email (optionnel)"
                            name="email"
                            icon="mail"
                            type="email"
                            placeholder="jean@example.com"
                            value="{{ old('email', auth()->user()->email) }}"
                        />

                        <div class="pt-4">
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary text-white px-6 py-3 text-sm font-semibold hover:bg-primary/90 transition-colors"
                            >
                                <i data-lucide="ticket" class="w-4 h-4"></i>
                                Générer mon pass {{ number_format($price, 0, ',', ' ') }} FCFA
                            </button>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                                Vous serez redirigé vers la page de paiement après confirmation.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary --}}
            <div class="lg:sticky lg:top-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700">
                    @php
                        $cover = null;
                        if ($visitPassable instanceof \App\Models\Property) {
                            $cover = $visitPassable->cover_image_url ?? ($visitPassable->images->first()->image_url ?? null);
                        } else {
                            $cover = optional($visitPassable->images->first())->url;
                        }
                    @endphp

                    @if($cover)
                        <img src="{{ $cover }}" alt="{{ $visitPassable->title ?? $visitPassable->titre }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <i data-lucide="home" class="w-12 h-12 text-gray-400 dark:text-gray-500"></i>
                        </div>
                    @endif

                    <div class="p-5">
                        <h3 class="font-display font-semibold text-lg dark:text-white mb-2">{{ $visitPassable->title ?? $visitPassable->titre }}</h3>

                        @php $cityName = optional($visitPassable->city ?? \App\Models\City::find($visitPassable->city_id ?? null))->name; @endphp
                        @if($cityName)
                            <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 mb-3">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                {{ $cityName }}
                            </div>
                        @endif

                        <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pass visite</span>
                                <span class="font-display font-bold text-xl text-primary dark:text-primary-400">
                                    {{ number_format($price, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection