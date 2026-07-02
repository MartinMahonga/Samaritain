@extends('layouts.base')

@section('title', 'Ajouter une parcelle')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajouter une parcelle</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Remplissez tous les champs pour publier votre parcelle</p>
            </div>
            <a href="{{ route('parcelles.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
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
                        <x-form.input name="quartier" label="Quartier *" placeholder="Ex: Bacongo" required />
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
                        <x-form.input name="prix" label="Prix (FCFA) *" type="number" step="1000"
                            placeholder="Ex: 5000000" required />
                    </div>
                </div>

                <!-- Statut & Titre foncier -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statut & Documentation</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                            <select name="statut"
                                class="w-full h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-500/20 focus:border-emerald-500 dark:focus:border-emerald-500">
                                <option value="disponible" {{ old('statut') === 'disponible' ? 'selected' : '' }}>Disponible
                                </option>
                                <option value="vendu" {{ old('statut') === 'vendu' ? 'selected' : '' }}>Vendu</option>
                                <option value="réservé" {{ old('statut') === 'réservé' ? 'selected' : '' }}>Réservé</option>
                            </select>
                        </div>
                        <x-form.input name="titre_foncier" label="Titre foncier" placeholder="Ex: TF-12345" />
                    </div>
                </div>

                <!-- Viabilité -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Viabilité</h2>
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="viabilisee" value="1" {{ old('viabilisee') ? 'checked' : '' }}
                            class="w-5 h-5 text-emerald-600 border-gray-300 dark:border-gray-700 rounded focus:ring-emerald-500 dark:focus:ring-emerald-500/20 cursor-pointer" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Parcelle viabilisée (eau, électricité, route d'accès)
                        </span>
                    </label>
                </div>

                <!-- Images -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Images</h2>
                    <x-form.file-input name="images" label="Images de la parcelle" accept="image/*"
                        multiple="{{ true }}" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Formats acceptés : JPG, PNG, GIF, WEBP. Taille max : 5MB par image.
                    </p>
                </div>

                <!-- Action buttons -->
                <div
                    class="p-6 bg-gray-50 dark:bg-gray-700/50 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('parcelles.index') }}"
                        class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white font-semibold rounded-lg transition-colors flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        Publier la parcelle
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
