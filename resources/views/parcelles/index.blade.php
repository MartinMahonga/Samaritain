@extends('layouts.base')

@section('title', 'Parcelles en vente')

@section('content')
    <!-- Hero Header with gradient background -->
    <div class="relative bg-primary text-primary-foreground min-h-[60vh] md:min-h-[70vh] flex items-center">
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6">
                Trouvez votre <span class="text-secondary">parcelle idéale</span>
            </h1>
            <p class="text-base sm:text-lg md:text-xl text-primary-foreground/80 mb-6 md:mb-8 max-w-2xl mx-auto">
                Découvrez notre sélection exclusive de parcelles disponibles
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        <!-- Search and Filters Section -->
        <div class="bg-card rounded-lg shadow-lg -mt-16 md:-mt-20 relative z-10 p-4 sm:p-6 mb-8 md:mb-12">
            <form action="{{ route('parcelles.index') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search by title -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-foreground/80 mb-2">Rechercher</label>
                        <div class="relative">
                            <input type="text" name="titre" id="titre"
                                placeholder="Ex: Grande parcelle..."
                                value="{{ request('titre') }}"
                                class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground pl-10">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-muted-foreground"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- City -->

                    <div>
                        <label class="block text-sm font-medium text-foreground/80 mb-2">Villes</label>
                        <select name="ville" id="ville"
                            class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground">
                            <option value="">Toutes les villes</option>
                            <option value="Brazzaville" {{ request('ville') === 'Brazzaville' ? 'selected' : '' }}>Brazzaville</option>
                            <option value="Pointe-noire" {{ request('ville') === 'Pointe-noire' ? 'selected' : '' }}>Pointe-noire</option>
                        </select>
                    </div>
                 
                    <div>
                        <label class="block text-sm font-medium text-foreground/80 mb-2">Quartier</label>
                        <select name="quartier" id="quartier"
                            class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground">
                            <option value="">Tous les quartiers</option>
                            <option value="Bacongo" {{ request('quartier') === 'Bacongo' ? 'selected' : '' }}>Bacongo</option>
                            <option value="Moungali" {{ request('quartier') === 'Moungali' ? 'selected' : '' }}>Moungali</option>
                            <option value="Ouenze" {{ request('quartier') === 'Ouenze' ? 'selected' : '' }}>Ouenze</option>
                        </select>
                    </div>
                   
                </div>

                <!-- Advanced Filters Toggle -->
                <div class="mt-4">
                    <button type="button" onclick="toggleAdvancedFilters()"
                        class="text-sm text-primary hover:text-primary/80 flex items-center gap-1 font-medium">
                        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filtres avancés
                    </button>

                    <div id="advancedFilters"
                        style="display: {{ request()->anyFilled(['prix_min', 'prix_max', 'superficie_min', 'viabilisee']) ? 'block' : 'none' }};"
                        class="mt-4 pt-4 border-t border-border">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Min Price -->
                            <div>
                                <label class="block text-sm font-medium text-foreground/80 mb-2">Prix min (FCFA)</label>
                                <input type="number" name="prix_min" id="prix_min"
                                    placeholder="0"
                                    value="{{ request('prix_min') }}"
                                    class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground">
                            </div>

                            <!-- Max Price -->
                            <div>
                                <label class="block text-sm font-medium text-foreground/80 mb-2">Prix max (FCFA)</label>
                                <input type="number" name="prix_max" id="prix_max"
                                    placeholder="Illimité"
                                    value="{{ request('prix_max') }}"
                                    class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground">
                            </div>

                            <!-- Min Surface -->
                            <div>
                                <label class="block text-sm font-medium text-foreground/80 mb-2">Superficie min (m²)</label>
                                <input type="number" name="superficie_min" id="superficie_min"
                                    placeholder="0"
                                    value="{{ request('superficie_min') }}"
                                    class="w-full px-4 py-2.5 border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent bg-background text-foreground">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('parcelles.index') }}"
                        class="px-6 py-2.5 border border-input rounded-lg text-muted-foreground hover:bg-muted transition-colors text-center font-medium">
                        Réinitialiser
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-primary text-primary-foreground rounded-lg font-semibold hover:bg-primary/90 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- Header with title and count -->
        <div id="parcelles"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-foreground">
                    Nos parcelles
                </h2>
                <p class="text-sm sm:text-base text-muted-foreground mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    {{ $parcelles->total() }} parcelle(s)
                </p>
            </div>

            <!-- Active filters -->
            @if (request()->anyFilled(['titre', 'ville', 'quartier', 'statut', 'prix_min', 'prix_max', 'superficie_min', 'viabilisee']))
                <div class="flex flex-wrap gap-2">
                    <span class="text-sm text-muted-foreground">Filtres actifs :</span>
                    @if (request('titre'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            {{ request('titre') }}
                        </span>
                    @endif
                    @if (request('ville'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            Ville: {{ request('ville') }}
                        </span>
                    @endif
                    @if (request('quartier'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            Quartier: {{ request('quartier') }}
                        </span>
                    @endif
                    {{-- @if (request('statut'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            {{ ucfirst(request('statut')) }}
                        </span>
                    @endif --}}
                    @if (request('prix_min'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            Prix ≥ {{ number_format(request('prix_min'), 0, ',', ' ') }} FCFA
                        </span>
                    @endif
                    @if (request('prix_max'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            Prix ≤ {{ number_format(request('prix_max'), 0, ',', ' ') }} FCFA
                        </span>
                    @endif
                    @if (request('superficie_min'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            Superficie ≥ {{ request('superficie_min') }} m²
                        </span>
                    @endif
                    @if (request('viabilisee'))
                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                            Viabilisée: {{ request('viabilisee') === '1' ? 'Oui' : 'Non' }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Parcelles Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 md:gap-24 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3 sm:gap-4">
            @forelse($parcelles as $parcelle)
                <x-parcelle-card :parcelle="$parcelle" />
            @empty
                <div class="col-span-full text-center py-16 sm:py-20">
                    <div class="text-muted-foreground mb-4">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                        </svg>
                    </div>
                    <p class="text-foreground text-lg sm:text-xl font-semibold mb-2">
                        Aucune parcelle trouvée
                    </p>
                    <p class="text-muted-foreground text-sm sm:text-base">
                        Essayez de modifier vos filtres ou de réinitialiser votre recherche.
                    </p>
                    <a href="{{ route('parcelles.index') }}"
                        class="inline-block mt-6 px-6 py-2.5 bg-primary text-primary-foreground font-semibold rounded-lg hover:bg-primary/90 transition-colors">
                        Réinitialiser les filtres
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($parcelles->hasPages())
            <div class="mt-8 sm:mt-12 text-muted-foreground">
                {{ $parcelles->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleAdvancedFilters() {
            const filters = document.getElementById('advancedFilters');
            if (filters.style.display === 'none') {
                filters.style.display = 'block';
            } else {
                filters.style.display = 'none';
            }
        }

        // Auto-submit on filter change (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const selects = ['statut', 'viabilisee'];
            selects.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('change', function() {
                        document.getElementById('filterForm').submit();
                    });
                }
            });
        });
    </script>
@endsection