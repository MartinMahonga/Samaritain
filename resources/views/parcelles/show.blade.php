@extends('layouts.base')

@section('title', $parcelle->titre)

@section('content')
    <div class="font-body bg-background dark:bg-gray-950 text-[#0F0E0C] dark:text-white antialiased min-h-screen">
        <div class="max-w-7xl mx-auto px-6 py-10 pb-20">

            {{-- Breadcrumb --}}
            <nav aria-label="Fil d'Ariane"
                class="flex items-center gap-2 text-xs text-[#6B6660] dark:text-gray-400 mb-10 font-body">
                <a href="{{ route('index') }}"
                    class="hover:text-primary dark:hover:text-primary-400 transition-colors">Accueil</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="{{ route('parcelles.index') }}"
                    class="hover:text-primary dark:hover:text-primary-400 transition-colors">Parcelles</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="dark:text-gray-300">{{ $parcelle->titre }}</span>
            </nav>

            {{-- Header --}}
            <header class="mb-8">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        {{-- Status --}}
                        <div class="flex items-center gap-3 mb-3">
                            @php
                                $statusStyles = [
                                    'disponible' =>
                                        'bg-[#D6F0DC] dark:bg-emerald-900/30 text-[#1E6B35] dark:text-emerald-400',
                                    'vendu' => 'bg-[#FEE2E2] dark:bg-red-900/30 text-[#991B1B] dark:text-red-400',
                                    'réservé' => 'bg-[#FEF3C7] dark:bg-amber-900/30 text-[#92400E] dark:text-amber-400',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center gap-1.5 text-[0.68rem] font-semibold tracking-widest px-3 py-1 rounded-full {{ $statusStyles[$parcelle->statut] ?? $statusStyles['disponible'] }}">
                                {{ ucfirst($parcelle->statut) }}
                            </span>
                            @if ($parcelle->viabilisee)
                                <span class="text-[0.68rem] font-medium tracking-widest text-[#6B6660] dark:text-gray-400">
                                    <i data-lucide="check-circle" class="w-3 h-3 inline text-emerald-500"></i>
                                    Viabilisée
                                </span>
                            @endif
                            <span class="text-[0.68rem] font-medium tracking-widest text-[#6B6660] dark:text-gray-400">
                                Réf: {{ $parcelle->reference }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h1 class="font-display font-semibold leading-[1.1] tracking-tight text-[#0F0E0C] dark:text-white"
                            style="font-size: clamp(2rem, 4.5vw, 3.5rem); max-width: 28ch;">
                            {{ $parcelle->titre }}
                        </h1>

                        {{-- Location --}}
                        <div class="flex items-center gap-2 mt-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#6B6660] dark:text-gray-400"></i>
                            <span class="text-[0.83rem] text-[#6B6660] dark:text-gray-400">
                                {{ $parcelle->quartier }}, {{ $parcelle->ville }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            {{-- ── Main grid ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-12 items-start">

                {{-- ── LEFT COLUMN ── --}}
                <div class="min-w-0">

                    {{-- Gallery --}}
                    <div class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 rounded-2xl overflow-hidden">
                            @if ($parcelle->imagePrincipale)
                                <div class="md:col-span-2 relative overflow-hidden bg-gray-100 dark:bg-gray-800"
                                    style="height: 400px;">
                                    <img src="{{ $parcelle->imagePrincipale->url }}" alt="{{ $parcelle->titre }}"
                                        class="w-full h-full object-cover" />
                                </div>
                            @endif

                            @if ($parcelle->images->count() > 1)
                                @foreach ($parcelle->images->skip(1)->take(3) as $image)
                                    <div class="relative overflow-hidden bg-gray-100 dark:bg-gray-800"
                                        style="height: 180px;">
                                        <img src="{{ $image->url }}" alt="Image {{ $loop->iteration }}"
                                            class="w-full h-full object-cover" />
                                    </div>
                                @endforeach
                            @endif

                            @if ($parcelle->images->isEmpty())
                                <div
                                    class="md:col-span-2 h-96 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center text-gray-400">
                                    <div class="text-center">
                                        <i data-lucide="image" class="w-16 h-16 mx-auto mb-2"></i>
                                        <span class="text-sm">Aucune image disponible</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Feature strip --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
                        <div class="bg-[#F7F6F4] dark:bg-gray-800/50 rounded-xl p-4 text-center">
                            <i data-lucide="land-plot" class="w-5 h-5 text-[#6B6660] dark:text-gray-400 mx-auto mb-1.5"></i>
                            <p
                                class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider">
                                Superficie</p>
                            <p class="text-sm font-bold text-[#0F0E0C] dark:text-white">
                                {{ number_format($parcelle->superficie, 0, ',', ' ') }} m²</p>
                        </div>

                        <div class="bg-[#F7F6F4] dark:bg-gray-800/50 rounded-xl p-4 text-center">
                            <i data-lucide="hash" class="w-5 h-5 text-[#6B6660] dark:text-gray-400 mx-auto mb-1.5"></i>
                            <p
                                class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider">
                                Référence</p>
                            <p class="text-sm font-bold text-[#0F0E0C] dark:text-white">{{ $parcelle->reference }}</p>
                        </div>

                        <div class="bg-[#F7F6F4] dark:bg-gray-800/50 rounded-xl p-4 text-center">
                            <i data-lucide="check-circle"
                                class="w-5 h-5 text-[#6B6660] dark:text-gray-400 mx-auto mb-1.5"></i>
                            <p
                                class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider">
                                Viabilisée</p>
                            <p
                                class="text-sm font-bold {{ $parcelle->viabilisee ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500' }}">
                                {{ $parcelle->viabilisee ? 'Oui' : 'Non' }}
                            </p>
                        </div>

                        <div class="bg-[#F7F6F4] dark:bg-gray-800/50 rounded-xl p-4 text-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-[#6B6660] dark:text-gray-400 mx-auto mb-1.5"></i>
                            <p
                                class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider">
                                Titre foncier</p>
                            <p class="text-sm font-bold text-[#0F0E0C] dark:text-white">
                                {{ $parcelle->titre_foncier ?: 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if ($parcelle->description)
                        <div class="mb-8">
                            <h2 class="font-display font-semibold text-xl text-[#0F0E0C] dark:text-white mb-3">Description
                            </h2>
                            <div class="prose prose-sm max-w-none text-[#6B6660] dark:text-gray-300 leading-relaxed">
                                {{ $parcelle->description }}
                            </div>
                        </div>
                    @endif

                    {{-- Images supplémentaires --}}
                    @if ($parcelle->images->count() > 4)
                        <div>
                            <h2 class="font-display font-semibold text-xl text-[#0F0E0C] dark:text-white mb-3">Galerie</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($parcelle->images->skip(4) as $image)
                                    <div class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800"
                                        style="height: 160px;">
                                        <img src="{{ $image->url }}" alt="Image {{ $loop->iteration }}"
                                            class="w-full h-full object-cover" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ── RIGHT COLUMN ── --}}
                <div class="lg:sticky lg:top-6 space-y-4">
                    {{-- Carte d'information --}}
                    <div
                        class="border border-accent rounded-2xl p-6">
                        <div class="space-y-4">
                            <div>
                                <p
                                    class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Prix</p>
                                <p class="text-2xl font-bold font-display text-primary">
                                    {{ number_format($parcelle->prix, 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                            <hr class="border-accent">

                            <div>
                                <p
                                    class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Statut</p>
                                <span
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-full {{ $statusStyles[$parcelle->statut] ?? $statusStyles['disponible'] }}">
                                    {{ ucfirst($parcelle->statut) }}
                                </span>
                            </div>

                            <hr class="border-accent">

                            <div>
                                <p
                                    class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Superficie</p>
                                <p class="text-sm font-medium text-[#0F0E0C] dark:text-white">
                                    {{ number_format($parcelle->superficie, 0, ',', ' ') }} m²
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Localisation</p>
                                <p class="text-sm text-[#6B6660] dark:text-gray-300">
                                    {{ $parcelle->quartier }}, {{ $parcelle->ville }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider mb-1">
                                    Référence</p>
                                <p class="text-sm text-[#6B6660] dark:text-gray-300">{{ $parcelle->reference }}</p>
                            </div>

                            @if ($parcelle->titre_foncier)
                                <div>
                                    <p
                                        class="text-[0.68rem] font-medium text-[#6B6660] dark:text-gray-400 uppercase tracking-wider mb-1">
                                        Titre foncier</p>
                                    <p class="text-sm text-[#6B6660] dark:text-gray-300">{{ $parcelle->titre_foncier }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('parcelles.contact.create', $parcelle) }}"
                            class="inline-flex w-full items-center justify-center gap-x-1.5 shrink-0 transition-colors duration-100 text-sm/5 font-medium shadow-none rounded-[var(--radius)] bg-[var(--primary)] text-[var(--primary-foreground)] hover:bg-[color-mix(in_oklab,var(--primary)_90%,transparent)] focus:bg-[color-mix(in_oklab,var(--primary)_90%,transparent)] active:bg-[var(--primary)] h-9 text-center px-4 py-2">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            Contacter l'agence
                        </a>

                        @can('update', $parcelle)
                            <x-btn href="{{ route('parcelles.edit', $parcelle) }}">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                Modifier la parcelle
                            </x-btn>
                        @endcan

                        @can('delete', $parcelle)
                            <form action="{{ route('parcelles.destroy', $parcelle) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <x-btn style="destructive" type="submit"
                                    class="w-full"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette parcelle ?')">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Supprimer
                                </x-btn>
                            </form>
                        @endcan

                        <x-btn href="{{ route('parcelles.index') }}" style="link">
                            <x-slot:prefix>
                                <i data-lucide="chevron-left"></i>
                            </x-slot:prefix>
                            Retour aux parcelles
                        </x-btn>
                    </div>
                </div>
            </div>

            {{-- Parcelles similaires --}}
            @if (isset($parcellesSimilaires) && $parcellesSimilaires->count() > 0)
                <div class="mt-16">
                    <h2 class="font-display font-semibold text-2xl text-[#0F0E0C] dark:text-white mb-6">Parcelles
                        similaires</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($parcellesSimilaires as $similaire)
                            <x-parcelle-card :parcelle="$similaire->toArray()" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
