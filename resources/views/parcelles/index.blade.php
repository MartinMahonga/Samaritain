{{-- resources/views/parcelles/index.blade.php --}}

@extends('layouts.base')

@section('title', 'Parcelles en vente')

@section('content')
    <x-blade-components::layout.container>
        <div class="flex items-center justify-between mb-6 mt-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Parcelles en vente</h1>
                <p class="text-sm text-gray-500 mt-1">Consultez et filtrez toutes les parcelles disponibles</p>
            </div>

            <a href="{{ route('parcelles.create') }}"
                class="flex items-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors duration-200">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Ajouter une parcelle
            </a>
        </div>

        <x-parcelles.carousel :parcelles="$parcelles" :filters="$filters" />
    </x-blade-components::layout.container>
@endsection
