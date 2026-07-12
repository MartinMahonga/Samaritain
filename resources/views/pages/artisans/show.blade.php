<x-app-layout>
    <div class="bg-background min-h-screen mb-3">
        <!-- Cover avec overlay amélioré -->
        <div class="relative h-64 md:h-80 lg:h-96 bg-gradient-to-br from-primary to-primary/80">
            @if ($artisan->cover)
                <img src="{{ Storage::url($artisan->cover) }}" alt="{{ $artisan->business_name }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary/80">
                    <div class="absolute inset-0 opacity-10"
                        style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 1px); background-size: 40px 40px;">
                    </div>
                </div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 md:-mt-24 lg:-mt-28 relative z-10">
            <!-- Header amélioré -->
            <div class="bg-card rounded-2xl shadow-xl border border-border p-6 md:p-8 mb-8 transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex flex-col md:flex-row md:items-start gap-6">
                    <!-- Avatar avec effet de cadre -->
                    <div class="relative">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl border-4 border-card shadow-lg overflow-hidden bg-gradient-to-br from-primary to-primary/80 flex-shrink-0">
                            @if ($artisan->avatar)
                                <img src="{{ Storage::url($artisan->avatar) }}" alt="{{ $artisan->business_name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary-foreground text-3xl font-bold">
                                    {{ substr($artisan->business_name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        @if ($artisan->verified)
                            <div class="absolute -bottom-1 -right-1 bg-success rounded-full p-1 border-2 border-card">
                                <svg class="w-4 h-4 text-success-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div>
                                <div class="flex items-center flex-wrap gap-2 mb-2">
                                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-foreground">
                                        {{ $artisan->business_name }}
                                    </h1>
                                    @if ($artisan->verified)
                                        <span class="bg-success/10 text-success px-2.5 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Vérifié
                                        </span>
                                    @endif
                                    @if($artisan->is_premium)
                                        <span class="bg-warning/10 text-warning px-2.5 py-1 rounded-full text-xs font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            Premium
                                        </span>
                                    @endif
                                </div>
                                <p class="text-primary font-semibold text-lg mb-3">{{ $artisan->profession }}</p>
                            </div>

                            <!-- Note améliorée -->
                            <div class="flex items-center gap-4 bg-muted rounded-xl px-4 py-3">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-foreground">{{ number_format($artisan->average_rating, 1) }}</div>
                                    <div class="flex text-warning justify-center mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($artisan->average_rating))
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @elseif($i - 0.5 <= $artisan->average_rating)
                                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" fill-opacity="0.5"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-muted-foreground fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-xs text-muted-foreground mt-1">{{ $artisan->reviews_count }} avis</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de contact -->
                        <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-border">
                            <div class="flex items-center gap-2 text-sm text-muted-foreground bg-muted px-3 py-1.5 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $artisan->city }}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-muted-foreground bg-muted px-3 py-1.5 rounded-lg">
                                <a href="tel:{{ $artisan->phone }}" target="_blank" rel="noopener noreferrer">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </a>
                            </div>
                            @if ($artisan->email)
                                <div class="flex items-center gap-2 text-sm text-muted-foreground bg-muted px-3 py-1.5 rounded-lg">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $artisan->email }}
                                </div>
                            @endif
                            @if ($artisan->experience)
                                <div class="flex items-center gap-2 text-sm text-muted-foreground bg-muted px-3 py-1.5 rounded-lg">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $artisan->experience }} an(s) d'expérience
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Description -->
                    @if ($artisan->bio)
                        <div class="bg-card rounded-2xl shadow-sm border border-border p-6 hover:shadow-md transition-all duration-300">
                            <h2 class="text-xl font-semibold text-foreground mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                À propos
                            </h2>
                            <p class="text-muted-foreground leading-relaxed">{{ $artisan->bio }}</p>
                        </div>
                    @endif

                    <!-- Spécialités -->
                    @if ($artisan->categories->isNotEmpty())
                        <div class="bg-card rounded-2xl shadow-sm border border-border p-6 hover:shadow-md transition-all duration-300">
                            <h2 class="text-xl font-semibold text-foreground mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Spécialités
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($artisan->categories as $category)
                                    <span class="bg-primary/10 text-primary px-3 py-1.5 rounded-full text-sm font-medium hover:bg-primary/20 transition-colors">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Réalisations -->
                    <div class="bg-card rounded-2xl shadow-sm border border-border p-6 hover:shadow-md transition-all duration-300">
                        <h2 class="text-xl font-semibold text-foreground mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Réalisations
                        </h2>
                        @if ($artisan->projects->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($artisan->projects as $project)
                                    <x-artisan.project-card :project="$project" />
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted-foreground text-center py-8">Aucune réalisation publiée pour le moment.</p>
                        @endif
                    </div>

                    <!-- Avis -->
                    <div class="bg-card rounded-2xl shadow-sm border border-border p-6" id="reviews">
                        <h2 class="text-xl font-semibold text-foreground mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Avis clients ({{ $artisan->reviews_count }})
                        </h2>

                        <!-- Formulaire d'avis amélioré -->
                        @auth
                            @if (!$userReview && auth()->id() !== $artisan->user_id)
                                <form action="{{ route('artisans.reviews.store', $artisan) }}" method="POST"
                                    class="mb-8 bg-gradient-to-r from-muted/50 to-card rounded-xl p-5 border border-border">
                                    @csrf
                                    <h3 class="font-semibold text-foreground mb-3">Laisser un avis</h3>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-muted-foreground mb-2">Votre note</label>
                                        <div class="flex gap-2" x-data="{ rating: 0 }">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <button type="button" @click="rating = {{ $i }}"
                                                    class="focus:outline-none transition-transform hover:scale-110">
                                                    <svg class="w-8 h-8 transition-colors"
                                                        :class="rating >= {{ $i }} ? 'text-warning fill-current' : 'text-muted-foreground'"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                </button>
                                            @endfor
                                            <input type="hidden" name="rating" :value="rating" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <x-form.textarea name="comment" label="Votre commentaire" placeholder="Partagez votre expérience..." />
                                    </div>
                                    <button type="submit"
                                        class="bg-primary text-primary-foreground px-6 py-2.5 rounded-xl hover:bg-primary/90 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                        Publier mon avis
                                    </button>
                                </form>
                            @elseif($userReview)
                                <div class="mb-6 bg-primary/5 border border-primary/20 rounded-xl p-4 text-center">
                                    <svg class="w-8 h-8 text-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-primary text-sm">Vous avez déjà laissé un avis pour cet artisan.</p>
                                </div>
                            @endif
                        @else
                            <div class="mb-6 bg-muted rounded-xl p-5 text-center border border-border">
                                <svg class="w-8 h-8 text-muted-foreground mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-muted-foreground text-sm">
                                    <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Connectez-vous</a> pour laisser un avis.
                                </p>
                            </div>
                        @endauth

                        <!-- Liste des avis -->
                        <div class="space-y-4">
                            @forelse($artisan->reviews as $review)
                                <x-artisan.review-card :review="$review" />
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-muted-foreground mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <p class="text-muted-foreground">Aucun avis pour le moment.</p>
                                    <p class="text-muted-foreground/70 text-sm mt-1">Soyez le premier à donner votre avis !</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>