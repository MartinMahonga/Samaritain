@extends('layouts.dashboard')

@section('title', 'Détails de la parcelle - ' . $parcelle->titre)

@section('content')
<div class="space-y-6">
    <!-- En-tête avec retour et actions -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.parcelle.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h1 class="text-gray-800 dark:text-white">Détails de la parcelle : {{ $parcelle->titre }}</h1>
        </div>
        <div class="flex gap-2">
            <x-btn href="{{ route('admin.parcelle.edit', $parcelle) }}" style="outline" class="dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                <x-slot:prefix>
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </x-slot:prefix>
                Modifier
            </x-btn>
        </div>
    </div>

    <x-container-dashed>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne gauche - Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations générales -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Informations générales</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Titre :</span>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $parcelle->titre }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Prix :</span>
                            <p class="font-medium text-gray-800 dark:text-white">{{ number_format($parcelle->prix, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Superficie :</span>
                            <p class="font-medium text-gray-800 dark:text-white">{{ number_format($parcelle->superficie, 2, ',', ' ') }} m²</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Localisation :</span>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $parcelle->localisation }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Quartier :</span>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $parcelle->quartier }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Ville :</span>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $parcelle->ville }}</p>
                        </div>
                       
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Créé par :</span>
                            <p class="font-medium text-gray-800 dark:text-white">{{ $parcelle->creator->name ?? 'Admin' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Description</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $parcelle->description ?? 'Aucune description fournie' }}</p>
                </div>
            </div>

            <!-- Colonne droite - Actions et images -->
            <div class="space-y-6">
                <!-- Images -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Images de la parcelle</h3>
                    @if($parcelle->images->count() > 0)
                        <div class="space-y-2">
                            @foreach($parcelle->images as $image)
                                <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <a href="{{ asset($image->url) }}" target="_blank">
                                        <img src="{{ asset($image->url) }}" class="w-full h-32 object-cover hover:opacity-75 transition" alt="Image">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Aucune image disponible</p>
                    @endif
                </div>

                <!-- Suppression -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-lg p-4 border border-red-200 dark:border-red-800">
                    <h3 class="font-semibold text-red-600 dark:text-red-400 mb-3">Zone dangereuse</h3>
                    <form action="{{ route('admin.parcelle.destroy', $parcelle) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette parcelle ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-400 font-medium py-2 px-4 rounded-lg text-sm transition flex items-center justify-center gap-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer avec métadonnées -->
        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 text-center">
            <small class="text-gray-400 dark:text-gray-500 text-xs">
                ID: {{ $parcelle->id }} | 
                Créé le: {{ $parcelle->created_at->format('d/m/Y à H:i') }} |
                Dernière modification: {{ $parcelle->updated_at->format('d/m/Y à H:i') }}
            </small>
        </div>
    </x-container-dashed>
</div>
@endsection