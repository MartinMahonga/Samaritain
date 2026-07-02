@extends('layouts.base')

@section('title', 'Tous les biens')

@section('content')
    <!-- Hero Header avec image de fond -->
    <div
        class="relative bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 dark:from-blue-800 dark:via-blue-900 dark:to-blue-950 text-white min-h-[90vh] md:min-h-[80vh] lg:min-h-[90vh] flex items-center">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2073&q=80"
                alt="Background" class="w-full h-full object-cover opacity-20 dark:opacity-30">
        </div>

        <div class="relative container mx-auto px-4 sm:px-6 lg:px-5 py-5 md:py-5 lg:py-20 text-center">
            <h1 class="text-2xl sm:text-4xl md:text-3xl lg:text-5xl xl:text-6xl font-bold mb-4 md:mb-6 px-2">
                Trouvez votre <span class="text-primary dark:text-primary-400">bien immobilier</span> idéal
            </h1>
            <p
                class="text-base sm:text-lg md:text-xl lg:text-2xl text-blue-100 dark:text-blue-200 mb-6 md:mb-10 max-w-3xl mx-auto px-4">
                Découvrez notre sélection de biens d'exception
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full">
                <path fill="#ffffff" fill-opacity="1" class="dark:fill-gray-900"
                    d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        <!-- Barre de recherche et filtres améliorée -->
        <div
            class="bg-card dark:bg-gray-800 rounded-lg shadow-lg -mt-16 md:-mt-20 lg:-mt-24 relative z-10 p-4 sm:p-6 mb-8 md:mb-12">
            <form action="{{ route('property.search') }}" method="GET" id="searchForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Recherche par mot-clé -->
                    <div class="lg:col-span-2">
                        <label
                            class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Rechercher</label>
                        <div class="relative">
                            <input type="text" name="keyword" id="keyword"
                                placeholder="Maison, appartement, localisation..." value="{{ request('keyword') }}"
                                class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 focus:border-ring dark:focus:border-primary bg-background dark:bg-gray-900 text-foreground dark:text-white pl-10">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-muted-foreground dark:text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Ville -->
                    <div>
                        <label class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Ville</label>
                        <select name="city_id" id="city_id"
                            class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 focus:border-ring dark:focus:border-primary bg-background dark:bg-gray-900 text-foreground dark:text-white">
                            <option value="">Toutes les villes</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Type de
                            bien</label>
                        <select name="category_id" id="category_id"
                            class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 focus:border-ring dark:focus:border-primary bg-background dark:bg-gray-900 text-foreground dark:text-white">
                            <option value="">Tous les types</option>
                            @php
                                $categories = \App\Models\Category::select(['id', 'name'])->get();
                            @endphp
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filtres avancés (toggle) -->
                <div class="mt-4">
                    <button type="button" onclick="toggleAdvancedFilters()"
                        class="text-sm text-primary dark:text-primary-400 hover:text-primary/80 dark:hover:text-primary-300 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filtres avancés
                    </button>

                    <div id="advancedFilters"
                        style="display: {{ request()->anyFilled(['min_price', 'max_price', 'surface', 'rooms', 'bedrooms']) ? 'block' : 'none' }};"
                        class="mt-4 pt-4 border-t border-border dark:border-gray-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Prix min -->
                            <div>
                                <label class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Prix
                                    min (FCFA)</label>
                                <input type="number" name="min_price" id="min_price" placeholder="0"
                                    value="{{ request('min_price') }}"
                                    class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 bg-background dark:bg-gray-900 text-foreground dark:text-white">
                            </div>

                            <!-- Prix max -->
                            <div>
                                <label class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Prix
                                    max (FCFA)</label>
                                <input type="number" name="max_price" id="max_price" placeholder="Illimité"
                                    value="{{ request('max_price') }}"
                                    class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 bg-background dark:bg-gray-900 text-foreground dark:text-white">
                            </div>

                            <!-- Surface min -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Surface
                                    min (m²)</label>
                                <input type="number" name="surface" id="surface" placeholder="0"
                                    value="{{ request('surface') }}"
                                    class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 bg-background dark:bg-gray-900 text-foreground dark:text-white">
                            </div>

                            <!-- Pièces -->
                            <div>
                                <label class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Nombre
                                    de pièces</label>
                                <select name="rooms" id="rooms"
                                    class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 bg-background dark:bg-gray-900 text-foreground dark:text-white">
                                    <option value="">Tous</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('rooms') == $i ? 'selected' : '' }}>{{ $i }} pièce(s)
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Chambres -->
                            <div>
                                <label class="block text-sm font-medium text-card-foreground dark:text-gray-300 mb-2">Nombre
                                    de
                                    chambres</label>
                                <select name="bedrooms" id="bedrooms"
                                    class="w-full px-4 py-2.5 border border-border dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-ring dark:focus:ring-primary/30 bg-background dark:bg-gray-900 text-foreground dark:text-white">
                                    <option value="">Toutes</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}
                                            chambre(s)</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('property.index') }}"
                        class="px-6 py-2.5 border border-border dark:border-gray-700 rounded-lg text-muted-foreground dark:text-gray-400 hover:bg-muted dark:hover:bg-gray-700 transition-colors text-center">
                        Réinitialiser
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-primary dark:bg-primary-600 text-primary-foreground dark:text-white rounded-lg font-semibold hover:bg-primary/90 dark:hover:bg-primary-700 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- En-tête avec titre et compteur -->
        <div id="properties"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8 mt-4 md:mt-8">
            <div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-foreground dark:text-white">Nos biens immobiliers
                </h2>
                <p class="text-sm sm:text-base text-muted-foreground dark:text-gray-400 mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    {{ $properties->total() }} bien(s) trouvé(s)
                </p>
            </div>

            <!-- Filtres actifs -->
            @if (request()->anyFilled([
                    'keyword',
                    'city_id',
                    'category_id',
                    'min_price',
                    'max_price',
                    'surface',
                    'rooms',
                    'bedrooms',
                ]))
                <div class="flex flex-wrap gap-2">
                    <span class="text-sm text-muted-foreground dark:text-gray-400">Filtres actifs :</span>
                    @if (request('keyword'))
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">Recherche:
                            {{ request('keyword') }}</span>
                    @endif
                    @if (request('city_id'))
                        @php $city = \App\Models\City::find(request('city_id')); @endphp
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">Ville:
                            {{ $city->name ?? '' }}</span>
                    @endif
                    @if (request('category_id'))
                        @php $category = \App\Models\Category::find(request('category_id')); @endphp
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">Type:
                            {{ $category->name ?? '' }}</span>
                    @endif
                    @if (request('min_price'))
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">Prix
                            ≥
                            {{ number_format(request('min_price'), 0, ',', ' ') }} FCFA</span>
                    @endif
                    @if (request('max_price'))
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">Prix
                            ≤
                            {{ number_format(request('max_price'), 0, ',', ' ') }} FCFA</span>
                    @endif
                    @if (request('surface'))
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">Surface
                            ≥
                            {{ request('surface') }} m²</span>
                    @endif
                    @if (request('rooms'))
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">{{ request('rooms') }}
                            pièce(s)</span>
                    @endif
                    @if (request('bedrooms'))
                        <span
                            class="px-2 py-1 bg-primary/10 dark:bg-primary-400/20 text-primary dark:text-primary-400 rounded-full text-xs">{{ request('bedrooms') }}
                            chambre(s)</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Grille des biens -->
        <div
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 md:gap-24 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3 sm:gap-4">
            @forelse($properties as $property)
                <x-ui.property-card :property="$property" />
            @empty
                <div class="col-span-full text-center py-12 sm:py-16">
                    <div class="text-muted-foreground dark:text-gray-500 mb-4">
                        <svg class="w-20 h-20 sm:w-24 sm:h-24 mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <p class="text-foreground dark:text-white text-lg sm:text-xl mb-2">Aucun bien trouvé</p>
                    <p class="text-muted-foreground dark:text-gray-400 text-sm sm:text-base">Aucun bien ne correspond à vos
                        critères de
                        recherche.</p>
                    <a href="{{ route('property.index') }}"
                        class="inline-block mt-4 px-6 py-2 bg-primary dark:bg-primary-600 text-primary-foreground dark:text-white rounded-lg hover:bg-primary/90 dark:hover:bg-primary-700 transition-colors">
                        Voir tous les biens
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($properties->hasPages())
            <div class="mt-8 sm:mt-12 text-gray-600 dark:text-gray-400">
                {{ $properties->appends(request()->query())->links() }}
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

        // Soumission automatique du formulaire lors du changement de filtre (optionnel)
        document.addEventListener('DOMContentLoaded', function() {
            const selects = ['city_id', 'category_id', 'rooms', 'bedrooms'];
            selects.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('change', function() {
                        document.getElementById('searchForm').submit();
                    });
                }
            });
        });
    </script>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        @media (max-width: 640px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
@endsection
