{{-- resources/views/parcelles/show.blade.php --}}

@extends('layouts.base')

@section('title', 'Détail parcelle')

@section('content')
    <x-blade-components::layout.container>
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-4xl mx-auto py-8">
            <a href="{{ route('parcelles.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-800 mb-6 transition-colors">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                Retour aux parcelles
            </a>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6 rounded-2xl overflow-hidden">
                @if ($parcelle->imagePrincipale)
                    <img src="{{ $parcelle->imagePrincipale->url }}" alt="{{ $parcelle->titre }}" class="w-full h-72 object-cover rounded-2xl" />
                @else
                    <div class="w-full h-72 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                        </svg>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $parcelle->titre }}</h1>
                        <div class="flex items-center gap-1 text-gray-500 text-sm mt-1">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            <span>{{ $parcelle->quartier }}, {{ $parcelle->ville }}</span>
                        </div>
                    </div>

                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $parcelle->statut === 'vendu' ? 'bg-red-100 text-red-700' : ($parcelle->statut === 'réservé' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                        {{ ucfirst($parcelle->statut) }}
                    </span>
                </div>

                <p class="text-3xl font-bold text-emerald-600 mb-6">{{ number_format($parcelle->prix, 0, ',', ' ') }} FCFA</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500 mb-1">Superficie</p>
                        <p class="font-bold text-gray-800">{{ number_format($parcelle->superficie, 0, ',', ' ') }} m²</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500 mb-1">Référence</p>
                        <p class="font-bold text-gray-800">{{ $parcelle->reference }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500 mb-1">Viabilisée</p>
                        <p class="font-bold {{ $parcelle->viabilisee ? 'text-emerald-600' : 'text-red-500' }}">{{ $parcelle->viabilisee ? 'Oui' : 'Non' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500 mb-1">Titre foncier</p>
                        <p class="font-bold text-gray-800">{{ $parcelle->titre_foncier ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

            @if ($parcelle->description)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                    <h2 class="text-lg font-bold text-gray-800 mb-2">Description</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $parcelle->description }}</p>
                </div>
            @endif

            <div class="flex items-center gap-3 mb-6">
                @can('update', $parcelle)
                    <a href="{{ route('parcelles.edit', $parcelle) }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors duration-200 flex items-center gap-2">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        Modifier
                    </a>
                @endcan

                @can('delete', $parcelle)
                    <form action="{{ route('parcelles.destroy', $parcelle) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette parcelle ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-6 py-2.5 rounded-xl transition-colors duration-200 flex items-center gap-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Supprimer
                        </button>
                    </form>
                @endcan
            </div>

            @if($parcelle->images->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($parcelle->images as $image)
                        <img src="{{ $image->url }}" alt="Image {{ $loop->iteration }}" class="w-full h-64 object-cover rounded-2xl" />
                    @endforeach
                </div>
            @endif
        </div>
    </x-blade-components::layout.container>
@endsection
