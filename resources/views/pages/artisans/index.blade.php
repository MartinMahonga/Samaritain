<x-app-layout>
    <!-- Hero Section avec pattern et animation -->

        <div class="relative w-full h-96 pt-20">
            {{-- Image de fond --}}
            <img src="{{ asset('/artis/ar1.jpg') }}" alt="Background"
                class="absolute inset-0 w-full h-full object-cover">

            {{-- Overlay sombre pour rendre le texte lisible --}}
            <div class="absolute inset-0 bg-black/50"></div>

            {{-- Contenu par dessus --}}
            <div class="relative z-10 flex flex-col items-center justify-center text-white ">
                <h1 class="relative flex items-center justify-center h-full text-primary text-6xl font-bold">
                    Marketplace <span class="text-white ml-5">Artisans</span>
                </h1>

                <p class="relative z-10 flex flex-col items-center justify-center h-full text-white px-4 text-lg italic mt-5">
                    Trouvez les meilleurs artisans pour vos projets immobiliers
                </p>

                <div class="relative z-10 flex  items-center justify-center h-full text-white px-4 mt-5">
                    <div class="flex items-center gap-2 text-sm text-white/90 dark:text-white/90">
                        500+ professionnels
                    </div>
                    <div class="flex items-center gap-2 text-sm text-white/90 dark:text-white/90">
                        <i data-lucide="map-pin" class="w-4 h-4 "></i>
                        <span>Partout au Congo</span>
                    </div>
                </div>
                
            </div>

    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filtres - Version améliorée -->
        <div class="rounded-2xl shadow-sm border border-accent dark:border-gray-700 mb-10 overflow-hidden bg-white dark:bg-gray-800"
            x-data="{ open: {{ request()->hasAny(['search', 'category', 'city', 'rating']) ? 'true' : 'false' }} }">

            <!-- En-tête des filtres -->
            <div class="px-6 py-4 border-b border-accent dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-accent dark:bg-gray-700 rounded-lg">
                            <i data-lucide="funnel" class="text-primary dark:text-primary-400"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filtres avancés</h2>
                        @if (request()->hasAny(['search', 'category', 'city', 'rating']))
                            <span
                                class="px-2 py-1 text-xs font-medium text-primary dark:text-primary-400 bg-primary/35 dark:bg-primary/20 rounded-full">
                                Filtres actifs
                            </span>
                        @endif
                    </div>
                    <button @click="open = !open"
                        class="group flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200">
                        <span class="text-sm font-medium" x-text="open ? 'Masquer' : 'Afficher'"></span>
                        <i data-lucide="chevron-down" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

            <!-- Corps des filtres -->
            <form action="{{ route('artisans.index') }}" method="GET" x-show="open" x-transition.duration.300ms>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <!-- Champ recherche -->
                        <x-form.input name="search" label="Recherche rapide" placeholder="Nom, métier" icon="search" />
                        <x-form.select name="category" label="Catégorie" placeholder="Toutes les catégories"
                            icon="drill" :options="$categories" :value="request('category')" />
                        <x-form.select name="city" label="Ville" placeholder="Toutes les villes" icon="building-2"
                            :options="$cities" :value="request('city')" />
                        <x-form.select name="rating" label="Note minimum" placeholder="Toutes les notes" icon="star"
                            :options="[
        '4' => '4+ étoiles',
        '3' => '3+ étoiles',
        '2' => '2+ étoiles',
    ]" :value="request('rating')" />
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-6 flex flex-wrap gap-3 pt-4 border-t border-accent dark:border-gray-700">
                        <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                            <x-slot:prefix>
                                <i data-lucide="funnel"></i>
                            </x-slot:prefix>
                            Appliquer les filtre
                        </x-btn>
                        <x-btn href="{{ route('artisans.index') }}" style="outline"
                            class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            <x-slot:prefix>
                                <i data-lucide="repeat"></i>
                            </x-slot:prefix>
                            Réinitialiser
                        </x-btn>
                    </div>
                </div>
            </form>
        </div>

        {{-- Filtres rapides par métier --}}
        <div class="mb-8 max-w-max">
            @php
                $serviceIcons = [
                    'macon' => 'hammer',
                    'plombier' => 'wrench',
                    'electricien' => 'zap',
                    'peintre' => 'paint-roller',
                    'menuisier' => 'ruler',
                    'carreleur' => 'grid-3x3',
                    'jardinier' => 'trees',
                    'serrurier' => 'key-round',
                    'climatisation' => 'wind',
                    'nettoyage' => 'sparkles',
                    'demenagement' => 'truck',
                ];

                $pillOptions = collect($categories)->mapWithKeys(
                    fn($label, $key) => [
                        $key => ['label' => $label, 'icon' => $serviceIcons[$key] ?? 'briefcase'],
                    ],
                );
            @endphp

            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Métiers populaires</h3>
            <x-form.pills name="category" :options="$pillOptions" />
        </div>

        <!-- En-tête des résultats -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Artisans disponibles</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $artisans->total() }} professionnels trouvés</p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 px-3 py-1.5 rounded-full">
                Tri par pertinence
            </div>
        </div>

        <!-- Liste artisans avec animation -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($artisans as $artisan)
                <div class="animate-fade-in-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                    <x-artisan.artisan-card :artisan="$artisan" />
                </div>
            @empty
                <div class="col-span-full">
                    <div
                        class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-6">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">Aucun artisan trouvé
                        </h3>
                        <p class="text-gray-400 dark:text-gray-500">Essayez de modifier vos critères de recherche</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination stylisée -->
        <div class="mt-12">
            <div class="p-4 text-gray-600 dark:text-gray-400">
                {{ $artisans->links() }}
            </div>
        </div>

        <!-- CTA Devenir artisan amélioré -->
        <div class="relative mt-16 bg-primary dark:bg-primary-800 rounded-2xl overflow-hidden shadow-2xl">
            <!-- Effet de brillance -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>

            <div class="relative px-8 py-12 text-center">
                <div class="inline-flex p-3 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-3">Vous êtes un professionnel ?</h2>
                <p class="text-white/90 dark:text-white/90 text-sm mb-8 max-w-xl mx-auto">
                    Rejoignez notre marketplace et développez votre activité en trouvant de nouveaux clients
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <x-btn href="{{ route('artisan.create') }}" style="secondary">
                        Devenir artisan
                        <i data-lucide="arrow-right"></i>
                    </x-btn>
                    <x-btn style="outline">En savoir plus</x-btn>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</x-app-layout>