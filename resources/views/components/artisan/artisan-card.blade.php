@props(['artisan'])

<a href="{{ route('artisans.show', $artisan) }}"
    class="group relative block rounded-3xl overflow-hidden aspect-[2/3] bg-muted shadow-sm hover:shadow-xl hover:shadow-foreground/10 transition-all duration-300">

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
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

    {{-- Contenu --}}
    <div class="absolute inset-x-0 bottom-0 p-5">
        <div class="flex items-center gap-1.5">
            <h3 class="text-white text-xl font-bold truncate">{{ $artisan->business_name }}</h3>
            @if($artisan->verified)
                <svg class="w-5 h-5 text-success shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Artisan vérifié">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>

        <p class="text-white/80 text-sm mt-1.5 line-clamp-2">
            {{ $artisan->profession }}@if($artisan->bio), {{ Str::limit($artisan->bio, 60) }}@endif
        </p>

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1 text-white/90 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-8a4 4 0 11-8 0 4 4 0 018 0zm6 3a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    {{ $artisan->reviews_count }}
                </span>
                <span class="flex items-center gap-1 text-white/90 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    {{ $artisan->projects->count() }}
                </span>
            </div>

            <span class="inline-flex items-center gap-1.5 bg-background text-foreground px-4 py-2 rounded-full text-sm font-semibold shadow-sm">
                voir le profil
            </span>
        </div>
    </div>
</a>