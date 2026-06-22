@extends('layouts.base')

@section('title', 'Mon espace')

@section('content')
    <x-blade-components::layout.container>

        {{-- Header --}}
        <section class="py-6">
            <div
                class="rounded-2xl bg-gradient-to-r from-primary to-primary/80 text-white p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">
                            Bonjour {{ auth()->user()->name }}
                        </h1>

                        <p class="text-white/80 mt-2">
                            Gérez vos biens, vos demandes et suivez votre activité.
                        </p>
                    </div>

                    <a href="{{ route('property.create') }}"
                        class="inline-flex items-center gap-2 bg-white text-primary px-4 py-2 rounded-xl font-medium hover:bg-gray-100 transition">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Publier un bien
                    </a>

                </div>
            </div>
        </section>

        {{-- Statistiques --}}
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <div class="bg-white rounded-2xl border p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Mes biens</p>
                        <h3 class="text-2xl font-bold mt-1">
                            {{ $propertiesCount ?? 0 }}
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                        <i data-lucide="building-2" class="w-5 h-5 text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Vues</p>
                        <h3 class="text-2xl font-bold mt-1">
                            {{ $viewsCount ?? 0 }}
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i data-lucide="eye" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Favoris</p>
                        <h3 class="text-2xl font-bold mt-1">
                            {{ $favoritesCount ?? 0 }}
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                        <i data-lucide="heart" class="w-5 h-5 text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Messages</p>
                        <h3 class="text-2xl font-bold mt-1">
                            {{ $messagesCount ?? 0 }}
                        </h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                        <i data-lucide="message-circle" class="w-5 h-5 text-green-600"></i>
                    </div>
                </div>
            </div>

        </section>

        {{-- Navigation --}}
        <section class="mb-8">
            <div class="bg-white border rounded-2xl p-2 flex gap-2 overflow-x-auto">

                <a href="{{ route('property.dashboard') }}"
                    @class([
                        'flex items-center gap-2 px-4 py-2 rounded-xl whitespace-nowrap transition',
                        'bg-primary text-white' => request()->routeIs('property.dashboard'),
                        'hover:bg-gray-100' => !request()->routeIs('property.dashboard'),
                    ])>
                    <i data-lucide="warehouse" class="w-4 h-4"></i>
                    Mes biens
                </a>

                @if(auth()->user()?->artisan)
                    <a href="{{ route('artisan.dashboard') }}"
                        @class([
                            'flex items-center gap-2 px-4 py-2 rounded-xl whitespace-nowrap transition',
                            'bg-primary text-white' => request()->routeIs('artisan.dashboard'),
                            'hover:bg-gray-100' => !request()->routeIs('artisan.dashboard'),
                        ])>
                        <i data-lucide="drill" class="w-4 h-4"></i>
                        Artisan
                    </a>
                @endif

                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-gray-100 whitespace-nowrap">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                    Activités
                </a>

                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-gray-100 whitespace-nowrap">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    Paramètres
                </a>

            </div>
        </section>

        {{-- Contenu --}}
        <section>
            @yield('dashboard-content')
        </section>

    </x-blade-components::layout.container>
@endsection