@extends('layouts.base')

@section('title', 'Accueil')

@php
    $properties = [
        [
            'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=1200&auto=format&fit=crop',
            'alt' => 'Appartement',
            'title' => 'Appartement familial à Paris',
            'location' => 'Paris 16e, France',
            'rating' => 4.95,
            'description' => '3 chambres • 120 m²',
            'price' => 1200,
            'badge' => 'Vérifier',
            'favorite' => true,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?q=80&w=1200&auto=format&fit=crop',
            'alt' => 'Maison',
            'title' => 'Loft industriel à Montréal',
            'location' => 'Montréal, Canada',
            'rating' => 4.88,
            'description' => '2 chambres • 90 m²',
            'price' => 1850,
            'favorite' => false,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1448630360428-65456885c650?q=80&w=1200&auto=format&fit=crop',
            'alt' => 'Maison de campagne',
            'title' => 'Maison de campagne',
            'location' => 'Bordeaux, France',
            'rating' => 4.99,
            'description' => '4 chambres • 180 m²',
            'price' => 2400,
            'badge' => 'Vérifier',
            'favorite' => false,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1448630360428-65456885c650?q=80&w=1200&auto=format&fit=crop',
            'alt' => 'Maison de campagne',
            'title' => 'Maison de campagne',
            'location' => 'Bordeaux, France',
            'rating' => 4.99,
            'description' => '4 chambres • 180 m²',
            'price' => 2400,
            'badge' => 'Vérifier',
            'favorite' => false,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1448630360428-65456885c650?q=80&w=1200&auto=format&fit=crop',
            'alt' => 'Maison de campagne',
            'title' => 'Maison de campagne',
            'location' => 'Bordeaux, France',
            'rating' => 4.99,
            'description' => '4 chambres • 180 m²',
            'price' => 2400,
            'badge' => 'Vérifier',
            'favorite' => false,
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1200&auto=format&fit=crop',
            'alt' => 'Maison luxe',
            'title' => 'Maison familiale avec piscine',
            'location' => 'Nice, France',
            'rating' => 5.0,
            'description' => '5 chambres • 250 m²',
            'price' => 3900,
            'favorite' => true,
        ],
    ];
@endphp

@section('content')
    <x-blade-components::layout.container>
        <header class="bg-primary mt-2 mb-2 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 p-12">
            <div class="flex flex-col gap-4">
                <h1 class="text-4xl md:text-7xl font-bold font-serif leading-[1.1]">
                    Vivez sereinement<br>
                    <span class="text-white font-serif italic">sans commission.</span>
                </h1>
                <p class="text-white">
                    Samaritain est l'agence qui ne prélève aucun frais de commission sur la location de nos biens
                    immobiliers.
                    Vous payez votre caution tout simplement.
                </p>

                <div class="relative w-full max-w-xl">
                    <div
                        class="absolute inset-y-0 right-0 flex items-center pointer-events-none text-gray-400 bg-primary p-3 m-1 rounded-full">
                        <label for="search" class="cursor-pointer">
                            <i data-lucide='search' color="#ffffff" width="18" height="18"></i>
                        </label>
                    </div>
                    <input type="text" id="search" placeholder="Rechercher un bien..."
                        class="bg-white focus:border-white focus:ring-white/10 md:h-12 w-xl rounded-4xl border-2 px-9 py-4 text-sm text-gray-800 focus:ring-3 focus:outline-hidden">
                </div>
            </div>
            <div>
                <img src="https://media.istockphoto.com/id/1165384568/fr/photo/complexe-moderne-europ%C3%A9en-de-b%C3%A2timents-r%C3%A9sidentiels.jpg?s=612x612&w=0&k=20&c=nvoIbiIffCt-nuj47Cc3I261Ke98iMouq_HefNM7Lz0="
                    alt="maison">
            </div>
        </header>

        <section 
            class="max-w-7xl mx-auto px-6 py-10"
            x-data="{
                    overflowing: false,
                    atStart: true,
                    atEnd: false,
                    init() {
                        this.update();
                        window.addEventListener('resize', () => this.update());
                    },
                    update() {
                        const carousel = this.$refs.carousel;
                        const scrollLeft = carousel.scrollLeft;
                        const clientWidth = carousel.clientWidth;
                        const scrollWidth = carousel.scrollWidth;
                        this.overflowing = scrollWidth > clientWidth;
                        this.atStart = scrollLeft <= 0;
                        this.atEnd = scrollLeft + clientWidth >= scrollWidth - 1;
                    },
                    scrollAmount() {
                        return Math.max(this.$refs.carousel.clientWidth * 0.8, 300);
                    },
                    prev() {
                        this.$refs.carousel.scrollBy({ left: -this.scrollAmount(), behavior: 'smooth' });
                        setTimeout(() => this.update(), 250);
                    },
                    next() {
                        this.$refs.carousel.scrollBy({ left: this.scrollAmount(), behavior: 'smooth' });
                        setTimeout(() => this.update(), 250);
                    }
                }" x-cloak>
            <div class="md:flex items-center md:justify-between mb-4">
                <div class="flex items-center gap-3 justify-between">
                    <h2 class="text-xl font-bold text-gray-800">Nos biens à découvrir</h2>

                    <button class="bg-gray-200 px-1 py-1 rounded-full">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="md:flex items-center gap-1 hidden">
                    <button @click="prev" class="rounded-full px-1 py-1"
                        :class="(atStart || atEnd) ? 'bg-gray-200 text-gray-800' : 'bg-gray-100 text-gray-300'">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <button @click="next" class="rounded-full px-1 py-1"
                        :class="(atStart || atEnd) ? 'bg-gray-100 text-gray-300' : 'bg-gray-200 text-gray-800'">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div class="relative">

                <style>
                    #carousel::-webkit-scrollbar {
                        display: none;
                    }

                    #carousel {
                        -ms-overflow-style: none;
                        scrollbar-width: none;
                    }
                </style>

                <div x-ref="carousel"
                    id="carousel"
                    class="flex gap-8 overflow-x-auto snap-x snap-mandatory scroll-smooth py-2 px-2"
                    style="-ms-overflow-style: none; scrollbar-width: none;">
                    @foreach ($properties as $property)
                        <div class="group cursor-pointer flex-shrink-0 w-72 md:w-80 lg:w-64 snap-start">
                            <div class="relative overflow-hidden rounded-2xl">
                                <img src="{{ $property['image'] }}" alt="{{ $property['alt'] }}"
                                    class="w-full h-42 object-cover transition duration-300 group-hover:scale-105" />

                                <button class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm p-2 rounded-full shadow">
                                    <i data-lucide="heart"
                                        class="w-4 h-4 {{ $property['favorite'] ? 'text-red-500' : 'text-gray-400' }}"></i>
                                </button>

                                @if (!empty($property['badge']))
                                    <span
                                        class="absolute flex gap-1 items-center bottom-3 left-3 bg-blue-200/60 text-blue-500 text-xs font-medium px-3 py-1 rounded-full shadow">
                                        <i data-lucide="badge-check" class="w-4 h-4"></i>
                                        {{ $property['badge'] }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3 space-y-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-xs text-gray-700">
                                            {{ $property['title'] }}
                                        </h3>

                                        <p class="text-gray-500 text-xs">
                                            {{ $property['location'] }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-1 text-xs font-medium">
                                        <i data-lucide="star" class="w-4 h-4 text-yellow-400"></i>
                                        {{ $property['rating'] }}
                                    </div>
                                </div>

                                <p class="text-gray-500 text-xs">
                                    {{ $property['description'] }}
                                </p>

                                <p class="text-gray-700 text-xs">
                                    <span class="font-semibold">{{ number_format($property['price'], 0, ',', ' ') }}FCFA</span>
                                    <span class="text-gray-500"> / mois</span>
                                </p>

                            </div>
                        </div>
                    @endforeach

                    <div class="flex flex-shrink-0 w-72 h-42 md:w-80 lg:w-64 snap-start items-center justify-center rounded-2xl bg-slate-50 p-6 shadow-sm">
                        <a href="#" class="inline-flex items-center justify-center w-full h-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            Tout afficher
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </x-blade-components::layout.container>
@endsection
