@extends('layouts.base')

@section('title', 'Ajouter un bien')

@section('content')
    @php
        $statusOptions = [
            'available' => 'Disponible',
            'sold' => 'Vendu',
            'rented' => 'Loué',
        ];
    @endphp

    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajouter un bien immobilier</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Remplissez tous les champs pour publier votre annonce</p>
            </div>
            <a href="{{ route('property.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 transition-colors">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>

        <div class="bg-sidebar dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('property.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Informations générales -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations générales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <x-form.input name="title" label="Titre du bien *" value="{{ old('title') }}" required />
                        </div>
                        <x-form.input name="surface" label="Surface (m²) *" type="number" step="0.01"
                            value="{{ old('surface') }}" required />
                        <x-form.input name="price" label="Prix (FCFA) *" type="number" step="1000"
                            value="{{ old('price') }}" required />
                    </div>
                </div>

                <!-- Description -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
                    <x-form.textarea name="description" label="Description du bien *" rows="6"
                        required>{{ old('description') }}</x-form.textarea>
                </div>

                <!-- Détails du bien -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Détails du bien</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <x-form.input name="rooms" label="Pièces" type="number" value="{{ old('rooms', 0) }}" />
                        <x-form.input name="bedrooms" label="Chambres" type="number" value="{{ old('bedrooms', 0) }}" />
                        <x-form.input name="bathrooms" label="Salle de bains" type="number"
                            value="{{ old('bathrooms', 0) }}" />
                        <x-form.input name="floor" label="Étage" type="number" value="{{ old('floor', 0) }}" />
                    </div>
                </div>

                <!-- Localisation -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Localisation</h2>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <x-form.input name="address" label="Adresse *" value="{{ old('address') }}" required />
                        </div>
                        <x-form.select name="city_id" label="Ville *" :options="$cities" placeholder="Sélectionnez une ville"
                            required />
                        <x-form.select name="arrondissement_id" label="Arrondissement" :options="$arrondissements"
                            placeholder="Sélectionnez un arrondissement" />
                        <x-form.select name="status" label="Statut *" :options="$statusOptions" placeholder="Choisir un statut"
                            required />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Position sur la carte
                        </label>
                        <x-ui.location-picker />
                    </div>
                </div>

                <!-- Catégorie et équipements -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Catégorie & équipements</h2>
                    <div class="space-y-4">
                        <x-form.select name="category_id" label="Catégorie *" :options="$categories"
                            placeholder="Choisir une catégorie" required />
                        <x-form.multi-select name="amenities" label="Équipements" :options="$amenities" />
                    </div>
                </div>

                <!-- Images -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Images du bien</h2>
                    <x-form.file-input name="images" label="Images" accept="image/*" multiple="{{ true }}" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Vous pouvez sélectionner plusieurs images. Formats acceptés : JPG, PNG, GIF
                    </p>
                </div>

                <!-- Boutons d'action -->
                <div class="p-6 bg-accent dark:bg-gray-700/50 flex justify-end items-center gap-3">
                    <x-btn href="{{ route('property.dashboard') }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Annuler</x-btn>
                    <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                        <x-slot:prefix>
                            <i data-lucide="check"></i>
                        </x-slot:prefix>
                        Publier le bien
                    </x-btn>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Filtrer les arrondissements par ville
            document.addEventListener('DOMContentLoaded', function() {
                const citySelect = document.getElementById('city_id');
                const arrondissementSelect = document.getElementById('arrondissement_id');

                if (citySelect && arrondissementSelect) {
                    // Sauvegarde des options originales
                    const originalOptions = Array.from(arrondissementSelect.querySelectorAll('option'));

                    function filterArrondissements() {
                        const cityId = citySelect.value;

                        // Réinitialiser et filtrer
                        arrondissementSelect.innerHTML = '<option value="">Sélectionnez un arrondissement</option>';

                        originalOptions.forEach(option => {
                            if (option.value === '') return;

                            const cityAttr = option.getAttribute('data-city');
                            if (!cityId || cityAttr === cityId) {
                                arrondissementSelect.appendChild(option.cloneNode(true));
                            }
                        });

                        // Déclencher l'événement change pour les erreurs de validation
                        arrondissementSelect.dispatchEvent(new Event('change'));
                    }

                    citySelect.addEventListener('change', filterArrondissements);
                    filterArrondissements(); // Exécuter au chargement
                }
            });
        </script>
    @endpush
@endsection