@extends('layouts.base')

@section('title', 'Modifier le bien - ' . $property->title)

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
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier le bien</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $property->title }}</p>
            </div>
            <a href="{{ route('property.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-400 transition-colors">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>

        <!-- Avertissement modification -->
        <div class="bg-amber-50 dark:bg-amber-950/30 border-l-4 border-amber-500 dark:border-amber-600 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5"></i>
                <p class="text-amber-800 dark:text-amber-300 text-sm">
                    Après modification, votre bien sera à nouveau soumis à validation par un administrateur.
                </p>
            </div>
        </div>

        <div class="bg-sidebar dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('property.update', $property) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Informations générales -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations générales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <x-form.input name="title" label="Titre du bien *" value="{{ $property->title }}" required />
                        </div>
                        <x-form.input name="surface" label="Surface (m²) *" type="number" step="0.01"
                            value="{{ $property->surface }}" required />
                        <x-form.input name="price" label="Prix (FCFA) *" type="number" step="1000"
                            value="{{ $property->price }}" required />
                    </div>
                </div>

                <!-- Description -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
                    <x-form.textarea name="description" label="Description du bien *" rows="6" :value="$property->description"
                        required />
                </div>

                <!-- Détails du bien -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Détails du bien</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <x-form.input name="rooms" label="Pièces" type="number" value="{{ $property->rooms }}" />
                        <x-form.input name="bedrooms" label="Chambres" type="number" value="{{ $property->bedrooms }}" />
                        <x-form.input name="bathrooms" label="Salle de bains" type="number"
                            value="{{ $property->bathrooms ?? 0 }}" />
                        <x-form.input name="floor" label="Étage" type="number" value="{{ $property->floor }}" />
                    </div>
                </div>

                <!-- Localisation -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Localisation</h2>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <x-form.input name="address" label="Adresse *" value="{{ $property->address }}" required />
                        </div>
                        <x-form.select name="city_id" label="Ville *" :options="$cities" placeholder="Sélectionnez une ville"
                            value="{{ $property->city_id }}" required />
                        <x-form.select name="arrondissement_id" label="Arrondissement" :options="$arrondissements"
                            placeholder="Sélectionnez un arrondissement" value="{{ $property->arrondissement_id }}"
                            data-city="{{ $property->city_id }}" />
                        <x-form.select name="status" label="Statut *" :options="$statusOptions" placeholder="Choisir un statut"
                            value="{{ $property->status->value ?? 'available' }}" required />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Position sur la carte
                        </label>
                        <x-ui.location-picker
                            :latitude="$property->latitude"
                            :longitude="$property->longitude"
                        />
                    </div>
                </div>

                <!-- Catégorie et équipements -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Catégorie & équipements</h2>
                    <div class="space-y-4">
                        <x-form.select name="category_id" label="Catégorie *" :options="$categories"
                            placeholder="Choisir une catégorie" value="{{ $property->category_id }}" required />
                        <x-form.multi-select name="amenities" label="Équipements" :options="$amenities" :value="$property->amenities->pluck('id')->toArray()" />
                    </div>
                </div>

                <!-- Images existantes -->
                @if($property->images->isNotEmpty())
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Images existantes</h2>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($property->images as $image)
                                <div class="relative group">
                                    <img src="{{ $image->image_url }}" class="w-24 h-24 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                                    <label class="absolute bottom-1 left-1 flex items-center gap-1 bg-black/70 text-white text-xs px-2 py-1 rounded-lg cursor-pointer">
                                        <input type="checkbox" name="kept_images[]" value="{{ $image->id }}" checked class="accent-primary">
                                        Conserver
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Nouvelles images -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ajouter des images</h2>
                    <x-form.file-input name="images" label="Images supplémentaires" accept="image/*" multiple="{{ true }}" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Vous pouvez ajouter plusieurs images. Formats acceptés : JPG, PNG, GIF
                    </p>
                </div>

                <!-- Boutons d'action -->
                <div class="p-6 bg-accent dark:bg-gray-700/50 flex justify-end items-center gap-3">
                    <x-btn href="{{ route('property.dashboard') }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Annuler</x-btn>
                    <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                        <x-slot:prefix>
                            <i data-lucide="save"></i>
                        </x-slot:prefix>
                        Mettre à jour
                    </x-btn>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const citySelect = document.getElementById('city_id');
                const arrondissementSelect = document.getElementById('arrondissement_id');

                if (citySelect && arrondissementSelect) {
                    // Sauvegarde des options originales avec leurs attributs data-city
                    const originalOptions = Array.from(arrondissementSelect.querySelectorAll('option'));
                    const currentValue = arrondissementSelect.value;

                    function filterArrondissements() {
                        const cityId = citySelect.value;

                        // Réinitialiser et filtrer
                        arrondissementSelect.innerHTML = '<option value="">Sélectionnez un arrondissement</option>';

                        originalOptions.forEach(option => {
                            if (option.value === '') return;

                            const cityAttr = option.getAttribute('data-city');
                            if (!cityId || cityAttr === cityId) {
                                const newOption = option.cloneNode(true);
                                arrondissementSelect.appendChild(newOption);
                            }
                        });

                        // Restaurer la valeur sélectionnée si elle existe toujours
                        if (currentValue && Array.from(arrondissementSelect.options).some(opt => opt.value ==
                                currentValue)) {
                            arrondissementSelect.value = currentValue;
                        }
                    }

                    citySelect.addEventListener('change', filterArrondissements);
                    filterArrondissements();
                }
            });
        </script>
    @endpush
@endsection