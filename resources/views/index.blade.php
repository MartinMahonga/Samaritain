@extends('layouts.base')

@section('title', 'Accueil')

@section('content')
    <x-blade-components::layout.container>
        <header class="bg-primary mt-2 mb-2 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 p-12">
            <div class="flex flex-col gap-4">
                <h1 class="text-4xl md:text-7xl font-bold font-serif leading-[1.1]">
                    Vivez sereinement<br>
                    <span class="text-white font-sans italic">Sans commission.</span>
                </h1>
                <p class="text-white">
                Samaritain est l'agence qui ne prélève aucun frais de commission sur la location de nos biens immobiliers. 
                    Vous payez votre caution tout simplement.
                </p>

                <div class="relative w-full max-w-xl">
                <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none text-gray-400 bg-primary p-3 m-1 rounded-full">
                        <label for="search" class="cursor-pointer">
                            <i data-lucide='search' color="#ffffff" width="18" height="18"></i>
                        </label>
                    </div>
                <input type="text" id="search" placeholder="Rechercher un bien..." class="bg-white focus:border-white focus:ring-white/10 md:h-12 w-xl rounded-4xl border-2 px-9 py-4 text-sm text-gray-800 focus:ring-3 focus:outline-hidden">
                </div>
            </div>
            <div>
            <img src="https://media.istockphoto.com/id/1165384568/fr/photo/complexe-moderne-europ%C3%A9en-de-b%C3%A2timents-r%C3%A9sidentiels.jpg?s=612x612&w=0&k=20&c=nvoIbiIffCt-nuj47Cc3I261Ke98iMouq_HefNM7Lz0=" alt="maison">
            </div>
        </header>
    </x-blade-components::layout.container>
@endsection
