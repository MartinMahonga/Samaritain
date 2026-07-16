<x-app-layout>
    <div class="bg-background min-h-screen pb-16">
        {{-- Bannière décorative --}}
        <div class="relative h-36 md:h-44 overflow-hidden">
            @if ($artisan->cover)
                <img src="{{ Storage::url($artisan->cover) }}" alt="{{ $artisan->business_name }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-background via-background/40 to-transparent"></div>
            @else
                <div class="absolute inset-0 bg-muted/40">
                    <div class="absolute top-1/3 left-10 w-72 h-72 bg-primary/15 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 right-1/4 w-96 h-48 bg-accent/40 rounded-full blur-3xl"></div>
                    <div class="absolute top-0 right-10 w-48 h-48 bg-warning/10 rounded-full blur-2xl"></div>
                </div>
            @endif
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- En-tête profil --}}
            <div class="relative -mt-16 md:-mt-20 mb-12">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                    <div class="flex flex-col sm:flex-row gap-6 sm:items-end">
                        {{-- Avatar squircle --}}
                        <div class="relative shrink-0">
                            <div class="w-32 h-32 md:w-40 md:h-40 rounded-[2rem] border-4 border-card shadow-xl shadow-foreground/10 overflow-hidden bg-gradient-to-br from-primary to-primary/80">
                                @if ($artisan->avatar)
                                    <img src="{{ Storage::url($artisan->avatar) }}" alt="{{ $artisan->business_name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-primary-foreground text-3xl md:text-4xl font-bold">
                                        {{ substr($artisan->business_name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            @if ($artisan->verified)
                                <div class="absolute -bottom-1.5 -right-1.5 bg-success rounded-full p-1.5 border-4 border-background shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-success-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Infos principales --}}
                        <div class="pb-1">
                            <div class="flex items-center flex-wrap gap-2 mb-1.5">
                                <h1 class="text-2xl md:text-3xl font-bold text-foreground tracking-tight">
                                    {{ $artisan->business_name }}
                                </h1>
                                @if ($artisan->is_premium)
                                    <span class="bg-warning text-warning-foreground px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Premium
                                    </span>
                                @endif
                                @if ($artisan->verified)
                                    <span class="bg-success/10 text-success px-2.5 py-1 rounded-full text-xs font-semibold">
                                        Vérifié
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted-foreground text-base mb-4">
                                {{ $artisan->profession }}
                                <span class="mx-1.5 text-border">·</span>
                                {{ $artisan->city }}
                                @if ($artisan->experience)
                                    <span class="mx-1.5 text-border">·</span>
                                    {{ $artisan->experience }} an(s) d'expérience
                                @endif
                            </p>

                            {{-- Boutons d'action --}}
                            <div class="flex flex-wrap gap-3">
                                <a href="tel:{{ $artisan->phone }}"
                                    class="inline-flex items-center gap-2 bg-foreground text-background px-6 py-2.5 rounded-2xl hover:bg-foreground/90 transition-all duration-200 font-medium text-sm shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Appeler
                                </a>
                                @if ($artisan->email)
                                    <a href="mailto:{{ $artisan->email }}"
                                        class="inline-flex items-center gap-2 bg-card text-foreground border border-border px-6 py-2.5 rounded-2xl hover:bg-muted transition-all duration-200 font-medium text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        Contacter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Statistiques --}}
                    <div class="flex items-center gap-8 lg:pb-2">
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-1 text-2xl md:text-3xl font-bold text-foreground">
                                {{ number_format($artisan->average_rating, 1) }}
                                <svg class="w-5 h-5 text-warning fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            </div>
                            <p class="text-xs text-muted-foreground mt-1">Note</p>
                        </div>
                        <div class="w-px h-10 bg-border"></div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-foreground">{{ $artisan->reviews_count }}</div>
                            <p class="text-xs text-muted-foreground mt-1">Avis</p>
                        </div>
                        <div class="w-px h-10 bg-border"></div>
                        <div class="text-center">
                            <div class="text-2xl md:text-3xl font-bold text-foreground">{{ $artisan->projects->count() }}</div>
                            <p class="text-xs text-muted-foreground mt-1">Réalisations</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation par onglets --}}
            <div x-data="{ tab: 'about' }" x-cloak>
                <nav class="flex gap-8 border-b border-border mb-10 overflow-x-auto scrollbar-hide">
                    @if ($artisan->bio || $artisan->categories->isNotEmpty())
                        <button x-on:click="tab = 'about'"
                            :class="tab === 'about' ? 'border-foreground text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground'"
                            class="pb-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap shrink-0">
                            À propos
                            @if ($artisan->categories->isNotEmpty())
                                <sup class="text-xs text-muted-foreground ml-0.5">{{ $artisan->categories->count() }}</sup>
                            @endif
                        </button>
                    @endif
                    <button x-on:click="tab = 'projects'"
                        :class="tab === 'projects' ? 'border-foreground text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground'"
                        class="pb-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap shrink-0">
                        Réalisations
                        <sup class="text-xs text-muted-foreground ml-0.5">{{ $artisan->projects->count() }}</sup>
                    </button>
                    <button x-on:click="tab = 'reviews'"
                        :class="tab === 'reviews' ? 'border-foreground text-foreground' : 'border-transparent text-muted-foreground hover:text-foreground'"
                        class="pb-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap shrink-0">
                        Avis
                        <sup class="text-xs text-muted-foreground ml-0.5">{{ $artisan->reviews_count }}</sup>
                    </button>
                </nav>

                {{-- Onglet À propos --}}
                @if ($artisan->bio || $artisan->categories->isNotEmpty())
                    <div x-show="tab === 'about'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        class="space-y-6">
                        @if ($artisan->bio)
                            <div class="bg-muted/50 rounded-3xl p-6 md:p-8">
                                <p class="text-muted-foreground leading-relaxed text-base md:text-lg">{{ $artisan->bio }}</p>
                            </div>
                        @endif

                        @if ($artisan->categories->isNotEmpty())
                            <div>
                                <h2 class="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-4">Spécialités</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($artisan->categories as $category)
                                        <span class="bg-primary/10 text-primary px-4 py-2 rounded-2xl text-sm font-medium">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Onglet Réalisations --}}
                <div x-show="tab === 'projects'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @if ($artisan->projects->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
                            @foreach ($artisan->projects as $project)
                                <div class="group bg-muted/40 rounded-3xl overflow-hidden hover:shadow-lg hover:shadow-primary/5 transition-all duration-300">
                                    <div class="relative overflow-hidden aspect-[4/3] m-3 mb-0 rounded-2xl">
                                        @if ($project->image)
                                            <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-muted flex items-center justify-center text-muted-foreground">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif

                                        {{-- Badge catégorie --}}
                                        @if ($project->category)
                                            <span class="absolute top-2.5 right-2.5 bg-primary text-primary-foreground w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold shadow-sm uppercase">
                                                {{ substr($project->category, 0, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-5 pt-4">
                                        <h4 class="font-semibold text-foreground text-base">{{ $project->title }}</h4>
                                        @if ($project->description)
                                            <p class="text-sm text-muted-foreground mt-1 line-clamp-2">{{ $project->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 bg-muted/30 rounded-3xl">
                            <svg class="w-12 h-12 text-muted-foreground/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-muted-foreground">Aucune réalisation publiée pour le moment.</p>
                        </div>
                    @endif
                </div>

                {{-- Onglet Avis --}}
                <div x-show="tab === 'reviews'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    id="reviews">
                    @auth
                        @if (! $userReview && auth()->id() !== $artisan->user_id)
                            <form action="{{ route('artisans.reviews.store', $artisan) }}" method="POST"
                                class="mb-8 bg-muted/40 rounded-3xl p-6 md:p-8 border border-border/50">
                                @csrf
                                <h3 class="font-semibold text-foreground mb-4">Laisser un avis</h3>
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-muted-foreground mb-2">Votre note</label>
                                    <div class="flex gap-2" x-data="{ rating: 0 }">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <button type="button" x-on:click="rating = {{ $i }}"
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
                                <div class="mb-5">
                                    <x-form.textarea name="comment" label="Votre commentaire" placeholder="Partagez votre expérience..." />
                                </div>
                                <button type="submit"
                                    class="bg-primary text-primary-foreground px-6 py-2.5 rounded-2xl hover:bg-primary/90 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                                    Publier mon avis
                                </button>
                            </form>
                        @elseif ($userReview)
                            <div class="mb-6 bg-primary/5 border border-primary/20 rounded-3xl p-5 text-center">
                                <svg class="w-8 h-8 text-primary mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-primary text-sm">Vous avez déjà laissé un avis pour cet artisan.</p>
                            </div>
                        @endif
                    @else
                        <div class="mb-6 bg-muted/40 rounded-3xl p-6 text-center border border-border/50">
                            <p class="text-muted-foreground text-sm">
                                <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Connectez-vous</a> pour laisser un avis.
                            </p>
                        </div>
                    @endauth

                    <div class="space-y-4">
                        @forelse ($artisan->reviews as $review)
                            <x-artisan.review-card :review="$review" />
                        @empty
                            <div class="text-center py-16 bg-muted/30 rounded-3xl">
                                <svg class="w-12 h-12 text-muted-foreground/50 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
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
</x-app-layout>