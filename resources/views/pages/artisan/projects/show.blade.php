<x-app-layout>
    <div class="bg-background min-h-screen pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Fil d'Ariane -->
            <nav class="flex items-center gap-2 text-sm text-muted-foreground mb-8">
                <a href="{{ route('artisans.index') }}" class="hover:text-primary transition-colors">Artisans</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('artisans.show', $artisan) }}" class="hover:text-primary transition-colors">{{ $artisan->business_name }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-foreground font-medium">{{ $project->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Galerie d'images - Colonne principale -->
                <div class="lg:col-span-2">
                    @php
                        $allImages = $project->images->pluck('image_url')->toArray();
                        if (empty($allImages) && $project->image) {
                            $allImages = [Storage::url($project->image)];
                        }
                    @endphp

                    @if (count($allImages) > 0)
                        <div class="space-y-4" x-data="{
                            currentIndex: 0,
                            images: {{ json_encode($allImages) }},
                            get currentImage() { return this.images[this.currentIndex] },
                            prev() {
                                this.currentIndex = this.currentIndex > 0 ? this.currentIndex - 1 : this.images.length - 1;
                            },
                            next() {
                                this.currentIndex = this.currentIndex < this.images.length - 1 ? this.currentIndex + 1 : 0;
                            },
                            init() {
                                document.addEventListener('keydown', (e) => {
                                    if (e.key === 'ArrowLeft') this.prev();
                                    if (e.key === 'ArrowRight') this.next();
                                });
                            }
                        }">
                            <!-- Image principale -->
                            <div class="relative bg-muted/30 rounded-3xl overflow-hidden group">
                                <div class="aspect-[16/10] relative">
                                    <img :src="currentImage" :alt="'{{ $project->title }} - Image ' + (currentIndex + 1)"
                                        class="w-full h-full object-cover transition-opacity duration-300"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100">
                                    
                                    <!-- Overlay navigation -->
                                    <div class="absolute inset-0 flex items-center justify-between px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button x-on:click="prev" class="p-2.5 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white dark:hover:bg-gray-800 transition-colors">
                                            <svg class="w-5 h-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button x-on:click="next" class="p-2.5 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white dark:hover:bg-gray-800 transition-colors">
                                            <svg class="w-5 h-5 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Compteur -->
                                    <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs px-2.5 py-1 rounded-full">
                                        <span x-text="currentIndex + 1"></span>/<span x-text="images.length"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Miniatures -->
                            <div class="flex gap-2 overflow-x-auto pb-2" style="-ms-overflow-style: none; scrollbar-width: none;">
                                <template x-for="(image, index) in images" :key="index">
                                    <button x-on:click="currentIndex = index"
                                        :class="currentIndex === index ? 'ring-2 ring-primary ring-offset-2 dark:ring-offset-gray-900' : 'opacity-60 hover:opacity-100'"
                                        class="shrink-0 w-20 h-16 rounded-xl overflow-hidden transition-all duration-200">
                                        <img :src="image" :alt="'Miniature ' + (index + 1)"
                                            class="w-full h-full object-cover">
                                    </button>
                                </template>
                            </div>
                        </div>
                    @else
                        <!-- Fallback si pas d'image -->
                        <div class="bg-muted/30 rounded-3xl aspect-[16/10] flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-muted-foreground/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-muted-foreground">Aucune image disponible</p>
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-foreground mb-3">Description</h2>
                        <div class="bg-muted/30 rounded-2xl p-6">
                            <p class="text-muted-foreground leading-relaxed">{{ $project->description ?? 'Aucune description fournie.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Infos du projet -->
                <div class="space-y-6">
                    <!-- Carte infos projet -->
                    <div class="bg-card rounded-2xl border border-border p-6">
                        <h1 class="text-2xl font-bold text-foreground mb-4">{{ $project->title }}</h1>
                        
                        <div class="space-y-4">
                            <!-- Artisan -->
                            <div class="flex items-center gap-3 pb-4 border-b border-border">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gradient-to-br from-primary to-primary/80 flex-shrink-0">
                                    @if ($artisan->avatar)
                                        <img src="{{ Storage::url($artisan->avatar) }}" alt="{{ $artisan->business_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-primary-foreground font-bold">
                                            {{ substr($artisan->business_name, 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground">Artisan</p>
                                    <a href="{{ route('artisans.show', $artisan) }}" class="font-semibold text-foreground hover:text-primary transition-colors">
                                        {{ $artisan->business_name }}
                                    </a>
                                    <p class="text-xs text-muted-foreground">{{ $artisan->profession }}</p>
                                </div>
                            </div>

                            <!-- Vues -->
                            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>{{ number_format($project->views) }} vues</span>
                            </div>

                            <!-- Date -->
                            <div class="flex items-center gap-3 text-sm text-muted-foreground">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Publié le {{ $project->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Contacter l'artisan -->
                    <div class="bg-primary/5 rounded-2xl border border-primary/10 p-6">
                        <h3 class="font-semibold text-foreground mb-2">Intéressé par ce travail ?</h3>
                        <p class="text-sm text-muted-foreground mb-4">Contactez {{ $artisan->business_name }} pour discuter de votre projet</p>
                        <a href="{{ route('artisans.show', $artisan) }}#contact"
                            class="inline-flex items-center justify-center w-full gap-2 bg-primary text-primary-foreground px-5 py-2.5 rounded-2xl hover:bg-primary/90 transition-all duration-200 font-medium text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Voir le profil
                        </a>
                    </div>

                    <!-- Nombre d'images -->
                    <div class="flex items-center gap-3 text-sm text-muted-foreground bg-muted/30 rounded-2xl p-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ count($allImages) }} photo(s) dans cette réalisation</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>