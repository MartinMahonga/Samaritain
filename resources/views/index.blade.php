@extends('layouts.base')

@section('title', 'Accueil')

@section('content')
    <x-blade-components::layout.container>
        <x-ui.hero-header :properties="$properties" />

        <x-ui.value-section/>
    
        <x-ui.carousel :properties="$properties" />

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
