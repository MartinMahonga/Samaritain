<section class="max-w-7xl mx-auto px-6 py-24">
    {{-- En-tête --}}
    <div class="mb-16">
        <p class="text-primary font-semibold uppercase tracking-widest mb-3 text-xs">Simple & rapide</p>
        <h2 class="text-4xl font-bold text-gray-900 dark:text-gray-300 font-display max-w-2xl">
            Commencez avec Samaritain<br>en trois étapes simples
        </h2>
        <p class="text-gray-500 dark:text-gray-400 mt-3 text-sm max-w-md">
            Trouvez votre logement en 3 étapes sans commission, sans intermédiaire.
        </p>
    </div>

    {{-- Grille principale --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        {{-- Colonne gauche : Étapes --}}
        <div class="space-y-12">
            {{-- Étape 1 --}}
            <div class="flex gap-5">
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-primary/40 dark:text-primary/30 font-display leading-none">1</span>
                    <div class="w-px h-16 bg-gray-200 dark:bg-gray-700 mt-3"></div>
                </div>
                <div class="pt-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="search" class="w-5 h-5 text-primary"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-300 font-display">Parcourez les biens</h3>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs leading-relaxed">
                        Filtrez par quartier, superficie ou budget. Tous nos biens sont vérifiés et disponibles en temps réel.
                    </p>
                </div>
            </div>

            {{-- Étape 2 --}}
            <div class="flex gap-5">
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-primary/40 dark:text-primary/30 font-display leading-none">2</span>
                    <div class="w-px h-16 bg-gray-200 dark:bg-gray-700 mt-3"></div>
                </div>
                <div class="pt-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar-check" class="w-5 h-5 text-primary"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-300 font-display">Planifiez une visite</h3>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs leading-relaxed">
                        Contactez-nous directement. Votre dossier est pris en charge sous 24h et la visite organisée à votre convenance.
                    </p>
                </div>
            </div>

            {{-- Étape 3 avec bouton --}}
            <div class="flex gap-5">
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-primary/40 dark:text-primary/30 font-display leading-none">3</span>
                </div>
                <div class="pt-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="key-round" class="w-5 h-5 text-primary"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-300 font-display">Emménagez</h3>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs leading-relaxed mb-6">
                        Signez votre contrat, récupérez vos clés. Zéro commission vous ne payez que votre loyer.
                    </p>
                    <a href="{{ route('property.index') }}"
                        class="inline-flex items-center bg-secondary dark:bg-primary text-white text-sm font-semibold px-7 py-3.5 rounded-full hover:scale-105 transition-all duration-300 shadow-lg shadow-secondary/30 dark:shadow-primary/30 hover:shadow-secondary/40 dark:hover:shadow-primary/40">
                        Découvrir
                    </a>
                </div>
            </div>
        </div>

        {{-- Colonne droite : Illustration moderne --}}
        <div class="hidden lg:block relative">
            {{-- Forme décorative jaune --}}
            <div class="absolute -top-8 -right-8 w-72 h-72 bg-yellow-200/60 dark:bg-yellow-200/20 rounded-full blur-2xl -z-10"></div>
            <div class="absolute -bottom-12 -left-12 w-56 h-56 bg-primary/10 dark:bg-primary/5 rounded-full blur-3xl -z-10"></div>

            {{-- Carte principale flottante --}}
            <div class="bg-white dark:bg-gray-800/90 rounded-3xl shadow-2xl shadow-gray-200/50 dark:shadow-gray-900/50 p-7 border border-gray-100/80 dark:border-gray-700/50 backdrop-blur-sm transition-all duration-300 hover:shadow-3xl hover:shadow-gray-300/40 dark:hover:shadow-gray-900/60 hover:-translate-y-1">
                {{-- En-tête de la carte --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 tracking-wider">Biens disponibles</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-300 font-display">28 logements</p>
                    </div>
                    <div class="flex -space-x-2">
                        <div class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-600 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-semibold text-gray-600 dark:text-gray-300">JD</div>
                        <div class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-600 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-semibold text-gray-600 dark:text-gray-300">MC</div>
                        <div class="w-9 h-9 rounded-full bg-primary/20 dark:bg-primary/30 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-semibold text-primary">+12</div>
                    </div>
                </div>

                {{-- Liste des biens --}}
                <div class="space-y-4">
                    {{-- Bien 1 --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50/80 dark:bg-gray-700/50 hover:bg-gray-100/80 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer group">
                        <div 
                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 flex-shrink-0 flex items-center justify-center text-2xl bg-[url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400&h=200&fit=crop&crop=center')] bg-cover"
                        >
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-300 truncate">Villa moderne</p>
                            <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                <i data-lucide="map-pin" class="w-3 h-3 inline"></i>
                                <span>Poto-Poto</span>
                            </div>
                        </div>
                        <span class="text-sm font-display font-bold text-primary dark:text-primary/90">450 000 FCFA</span>
                    </div>

                    {{-- Bien 2 --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50/80 dark:bg-gray-700/50 hover:bg-gray-100/80 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer group">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 flex-shrink-0 flex items-center justify-center text-2xl bg-[url('https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400&h=200&fit=crop&crop=center')] bg-cover bg-center"
                        >
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-300 truncate">Appartement F3</p>
                            <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                <i data-lucide="map-pin" class="w-3 h-3 inline"></i>
                                <span>Talangaï</span>
                            </div>
                        </div>
                        <span class="text-sm font-display font-bold text-primary dark:text-primary/90">320 000 FCFA</span>
                    </div>

                    {{-- Bien 3 --}}
                    <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50/80 dark:bg-gray-700/50 hover:bg-gray-100/80 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer group">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 flex-shrink-0 flex items-center justify-center text-2xl bg-[url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=400&h=200&fit=crop&crop=center')] bg-cover bg-center"
                        >
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-300 truncate">Maison avec jardin</p>
                            <div class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                <i data-lucide="map-pin" class="w-3 h-3 inline"></i>
                                <span>Bacongo</span>
                            </div>
                        </div>
                        <span class="text-sm font-display font-bold text-primary dark:text-primary/90">550 000 FCFA</span>
                    </div>
                </div>

                {{-- Badges de localisation --}}
                <div class="flex flex-wrap gap-2 mt-6 pt-5 border-t border-gray-100 dark:border-gray-700/50">
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-3 py-1.5 rounded-full">
                        <i data-lucide="map-pin" class="w-3 h-3 text-primary"></i> Makélékélé
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-3 py-1.5 rounded-full">
                        <i data-lucide="map-pin" class="w-3 h-3 text-primary"></i> Bacongo
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-3 py-1.5 rounded-full">
                        <i data-lucide="map-pin" class="w-3 h-3 text-primary"></i> Talangaï
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-3 py-1.5 rounded-full">
                        <i data-lucide="map-pin" class="w-3 h-3 text-primary"></i> Moungali
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700/50 px-3 py-1.5 rounded-full">
                        <i data-lucide="map-pin" class="w-3 h-3 text-primary"></i> Poto-Poto
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>