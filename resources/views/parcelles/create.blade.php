@extends('layouts.base')

@section('title', 'Ajouter une parcelle')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajouter une parcelle</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Remplissez tous les champs pour publier votre parcelle</p>
            </div>
            <a href="{{ route('parcelles.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('parcelles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-0">
                @csrf

                <!-- Informations générales -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations générales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-form.input name="titre" label="Titre *" placeholder="Ex: Grande parcelle résidentielle"
                                required />
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
                    <x-form.textarea name="description" label="Description de la parcelle" rows="5"
                        placeholder="Décrivez les caractéristiques principales de la parcelle..." />
                </div>

                <!-- Localisation -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Localisation</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $cities = [
                                'Brazzaville' => 'Brazzaville',
                                'Pointe-Noire' => 'Pointe-Noire',
                            ];
                        @endphp
                        <x-form.select name="ville" label="Ville" :options="$cities" placeholder="Choisir une ville"
                            required />
                        <x-form.select name="arrondissement_id" label="Arrondissement *" :options="$arrondissements"
                            placeholder="Choisir un arrondissement" required />
                        <x-form.input name="localisation" label="Localisation précise *" placeholder="Ex: Nord de Bacongo"
                            required />
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Caractéristiques</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form.input name="superficie" label="Superficie (m²) *" type="number" step="0.01"
                            placeholder="Ex: 500" required />
                        <x-form.input name="prix" label="Prix (FCFA) *" type="number" step="1000" placeholder="Ex: 5000000"
                            required />
                    </div>
                </div>

                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="conditions" value="1" {{ old('conditions') ? 'checked' : '' }} required
                            class="w-5 h-5 text-emerald-600 border-gray-300 dark:border-gray-700 rounded focus:ring-emerald-500 dark:focus:ring-emerald-500/20 cursor-pointer" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            J'accepte les
                            <a href="{{ route('conditions') }}" target="_blank" class="text-primary">
                                conditions d'utilisation
                            </a> *
                        </span>
                    </label>
                    @error('conditions')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Images -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Images</h2>
                    <x-form.file-input name="images" label="Images de la parcelle" accept="image/*" multiple="{{ true }}" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Formats acceptés : JPG, PNG, GIF, WEBP. Taille max : 5MB par image.
                    </p>
                </div>

                <!-- Action buttons -->
                <div
                    class="p-6 bg-gray-50 dark:bg-gray-700/50 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                    <x-btn style="outline" href="{{ route('parcelles.index') }}">
                        Annuler
                    </x-btn>
                    <x-btn type="submit">
                        <x-slot:prefix>
                            <i data-lucide="check"></i>
                        </x-slot:prefix>
                        Publier la parcelle
                    </x-btn>
                </div>

            </form>
        </div>
    </div>
@endsection