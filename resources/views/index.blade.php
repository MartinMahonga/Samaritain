@extends('layouts.base')

@section('title', 'Accueil')

@section('content')
    <x-blade-components::layout.container>
        <x-ui.hero-header :properties="$propertyCount" />

        <x-ui.value-section />

        <x-ui.carousel :properties="$properties" />

        {{-- Carousel parcelles --}}
        <section class="max-w-7xl mx-auto px-6 pb-12" x-data="{
            atStart: true,
            atEnd: false,
            init() {
                this.update();
                window.addEventListener('resize', () => this.update());
            },
            update() {
                const c = this.$refs.carouselParcelles;
                this.atStart = c.scrollLeft <= 0;
                this.atEnd = Math.ceil(c.scrollLeft + c.clientWidth) >= c.scrollWidth - 1;
            },
            scrollAmount() {
                const cardWidth = window.innerWidth < 640 ? 240 : 300;
                return Math.max(cardWidth + 20, 280);
            },
            prev() {
                this.$refs.carouselParcelles.scrollBy({ left: -this.scrollAmount(), behavior: 'smooth' });
                setTimeout(() => this.update(), 350);
            },
            next() {
                this.$refs.carouselParcelles.scrollBy({ left: this.scrollAmount(), behavior: 'smooth' });
                setTimeout(() => this.update(), 350);
            }
        }" x-cloak>
            <div class="flex items-center justify-between mb-5">
                <div>
                    <div class="flex items-center gap-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-300">Nos parcelles à vendre</h2>
                        <a href="{{ route('parcelles.index') }}"
                            class="hidden md:inline-flex items-center gap-1.5 text-xs font-medium text-primary border border-primary/30 px-3 py-1.5 rounded-full hover:bg-primary/5 transition">
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">Sélection de parcelles disponibles dès maintenant</p>
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

            <div x-ref="carouselParcelles" @scroll="update()" class="flex gap-5 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2"
                style="-ms-overflow-style: none; scrollbar-width: none;">
                @forelse ($parcelles as $parcelle)
                    <div class="flex-shrink-0 w-[80vw] sm:w-[45vw] md:w-[38vw] lg:w-[28vw] xl:w-[22vw] snap-start">
                        <x-parcelle-card :parcelle="$parcelle" />
                    </div>
                @empty
                    <p class="text-gray-400 text-sm py-8">Aucune parcelle disponible pour le moment.</p>
                @endforelse

                <div class="flex-shrink-0 w-56 snap-start">
                    <a href="{{ route('parcelles.index') }}"
                        class="flex flex-col items-center justify-center h-full rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary/40 hover:bg-primary/5 transition p-6 text-center group"
                        style="min-height: 176px;">
                        <div class="w-10 h-10 bg-gray-100 group-hover:bg-primary/10 rounded-full flex items-center justify-center mb-3 transition">
                            <i data-lucide="arrow-right" class="w-5 h-5 text-gray-400 group-hover:text-primary transition"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-600 group-hover:text-primary transition">Voir toutes les parcelles</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $parcelles->count() }}+ disponibles</p>
                    </a>
                </div>
            </div>

            <div class="flex justify-center gap-2 mt-4 md:hidden">
                <a href="{{ route('parcelles.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-primary border border-primary/30 px-4 py-2 rounded-full">
                    Voir toutes les parcelles
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </section>

        <x-how-it-works />

        <x-services />

        <x-popular-districts />


        <x-faq />
        <x-why-no-commission />

        <section class="max-w-7xl mx-auto px-6 pb-12">
            <div class="bg-gray-900 rounded-2xl px-8 py-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-white">Un bien en tête ?</h2>
                    <p class="text-gray-400 text-sm mt-1">Contactez-nous votre dossier est traité en 24h.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('property.index') }}"
                        class="inline-flex items-center gap-2 bg-white text-gray-900 text-xs md:text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-gray-100 transition">
                        <i data-lucide="home" class="md:w-4 md:h-4 h-6 w-6"></i>
                        Parcourir les biens
                    </a>
                    <a href="#"
                        class="inline-flex items-center gap-2 bg-primary text-white text-xs md:text-sm font-semibold px-5 py-2.5 rounded-full hover:opacity-90 transition">
                        <i data-lucide="phone" class="md:w-4 md:h-4 h-6 w-6"></i>
                        Nous contacter
                    </a>
                </div>
            </div>
        </section>

    </x-blade-components::layout.container>
@endsection
