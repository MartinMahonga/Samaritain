@props(['artisan'])

<a href="{{ route('artisans.show', $artisan) }}"
    class="group relative block rounded-3xl overflow-hidden aspect-[4/5] bg-muted shadow-sm hover:shadow-xl hover:shadow-foreground/10 transition-all duration-300">

    {{-- Photo --}}
    @if($artisan->avatar)
        <img src="{{ Storage::url($artisan->avatar) }}" alt="{{ $artisan->business_name }}"
            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center">
            <span class="text-primary-foreground text-6xl font-bold">{{ substr($artisan->business_name, 0, 1) }}</span>
        </div>
    @endif

    {{-- Dégradé --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>

    {{-- Contenu --}}
    <div class="absolute inset-x-0 bottom-0 p-4">
        <div class="flex items-center gap-1.5">
            <h3 class="text-white text-lg font-bold truncate">{{ $artisan->business_name }}</h3>
            @if($artisan->verified)
                <svg class="w-4 h-4 text-success shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Artisan vérifié">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>

        <p class="text-white/80 text-xs mt-1 truncate">{{ $artisan->profession }}</p>

        <div class="flex items-center justify-between mt-3">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-warning fill-current" viewBox="0 0 20 20">
                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                </svg>
                <span class="text-white text-sm font-semibold">{{ number_format($artisan->average_rating, 1) }}</span>
                <span class="text-white/70 text-xs">({{ $artisan->reviews_count }} avis)</span>
            </div>

            <span class="inline-flex items-center gap-1 bg-background text-foreground px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm">
                Voir le profil
            </span>
        </div>
    </div>
</a>