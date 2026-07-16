@props([
    'properties' => null,
])

<section class="max-w-7xl mx-auto px-6 pb-12" x-data="{
    atStart: true,
    atEnd: false,
    init() {
        this.update();
        window.addEventListener('resize', () => this.update());
    },
    update() {
        const c = this.$refs.carousel;
        this.atStart = c.scrollLeft <= 0;
        this.atEnd = c.scrollLeft + c.clientWidth >= c.scrollWidth - 1;
    },
    scrollAmount() {
        return Math.max(this.$refs.carousel.clientWidth * 0.8, 300);
    },
    prev() {
        this.$refs.carousel.scrollBy({ left: -this.scrollAmount(), behavior: 'smooth' });
        setTimeout(() => this.update(), 300);
    },
    next() {
        this.$refs.carousel.scrollBy({ left: this.scrollAmount(), behavior: 'smooth' });
        setTimeout(() => this.update(), 300);
    }
}" x-cloak>

    {{-- En-tête section --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-300">Nos biens à découvrir</h2>
                <a href="{{ route('property.index') }}"
                    class="hidden md:inline-flex items-center gap-1.5 text-xs font-medium text-primary border border-primary/30 px-3 py-1.5 rounded-full hover:bg-primary/5 transition">
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">Sélection de biens disponibles dès maintenant</p>
        </div>

        <div class="flex items-center gap-2">
            <button x-on:click="prev" :disabled="atStart"
                :class="atStart ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-200'"
                class="hidden md:flex w-8 h-8 items-center justify-center rounded-full bg-gray-100 transition">
                <i data-lucide="chevron-left" class="w-4 h-4 text-gray-700"></i>
            </button>

            <button x-on:click="next" :disabled="atEnd"
                :class="atEnd ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-200'"
                class="hidden md:flex w-8 h-8 items-center justify-center rounded-full bg-gray-100 transition">
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-700"></i>
            </button>
        </div>
    </div>

    {{-- Carousel --}}
    <div x-ref="carousel" @scroll="update()" class="flex gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2"
        style="-ms-overflow-style: none; scrollbar-width: none;">

        <style>
            #carousel-properties::-webkit-scrollbar {
                display: none;
            }
        </style>

        @foreach ($properties as $property)
            <x-ui.property-card :property="$property" />
        @endforeach

        {{-- Carte "Tout afficher" --}}
        <div class="flex-shrink-0 w-56 snap-start">
            <a href="{{ route('property.index') }}"
                class="flex flex-col items-center justify-center h-full rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary/40 hover:bg-primary/5 transition p-6 text-center group"
                style="min-height: 176px;">
                <div
                    class="w-10 h-10 bg-gray-100 group-hover:bg-primary/10 rounded-full flex items-center justify-center mb-3 transition">
                    <i data-lucide="arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-primary transition"></i>
                </div>
                <p class="text-sm font-semibold text-gray-600 group-hover:text-primary transition">Voir tous les
                    biens</p>
                <p class="text-xs text-gray-400 mt-1">{{ $properties->count() }}+ disponibles</p>
            </a>
        </div>

    </div>

    {{-- Navigation mobile --}}
    <div class="flex justify-center gap-2 mt-4 md:hidden">
        <a href="{{ route('property.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-medium text-primary border border-primary/30 px-4 py-2 rounded-full">
            Voir tous les biens
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
    </div>
</section>
