@extends('layouts.base')

@section('title', 'Modifier la parcelle - ' . $parcelle->titre)

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier la parcelle</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $parcelle->titre }}</p>
            </div>
            <a href="{{ route('parcelles.dashboard', $parcelle) }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                Retour
            </a>
        </div>

        <!-- Alert about validation -->
        <div class="bg-amber-50 dark:bg-amber-950/30 border-l-4 border-amber-500 dark:border-amber-600 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5"></i>
                <p class="text-amber-800 dark:text-amber-300 text-sm">
                    Après modification, votre parcelle sera à nouveau soumise à validation par un administrateur.
                </p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('parcelles.update', $parcelle) }}" method="POST" enctype="multipart/form-data"
                class="space-y-0">
                @csrf
                @method('PUT')

                <!-- Informations générales -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations générales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-form.input name="titre" label="Titre *" placeholder="Ex: Grande parcelle résidentielle"
                                value="{{ $parcelle->titre }}" />
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h2>
                    <x-form.textarea name="description" label="Description de la parcelle" rows="5"
                        placeholder="Décrivez les caractéristiques principales de la parcelle..."
                        value="{{ $parcelle->description }}" />
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
                            value="{{ $parcelle->ville }}" required />
                        <x-form.input name="quartier" label="Quartier *" placeholder="Ex: Bacongo"
                            value="{{ $parcelle->quartier }}" />
                        <x-form.input name="localisation" label="Localisation précise *" placeholder="Ex: Nord de Bacongo"
                            value="{{ $parcelle->localisation }}" />
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Caractéristiques</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form.input name="superficie" label="Superficie (m²) *" type="number" step="0.01"
                            placeholder="Ex: 500" value="{{ $parcelle->superficie }}" />
                        <x-form.input name="prix" label="Prix (FCFA) *" type="number" step="1000"
                            placeholder="Ex: 5000000" value="{{ $parcelle->prix }}" />
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
                                <option value="disponible"
                                    {{ old('statut', $parcelle->statut) === 'disponible' ? 'selected' : '' }}>Disponible
                                </option>
                                <option value="vendu"
                                    {{ old('statut', $parcelle->statut) === 'vendu' ? 'selected' : '' }}>Vendu</option>
                                <option value="réservé"
                                    {{ old('statut', $parcelle->statut) === 'réservé' ? 'selected' : '' }}>Réservé</option>
                            </select>
                        </div>
                        <x-form.input name="titre_foncier" label="Titre foncier" placeholder="Ex: TF-12345"
                            value="{{ $parcelle->titre_foncier }}" />
                    </div>
                </div>

                <!-- Viabilité -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Viabilité</h2>
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="viabilisee" value="1"
                            {{ old('viabilisee', $parcelle->viabilisee) ? 'checked' : '' }}
                            class="w-5 h-5 text-emerald-600 border-gray-300 dark:border-gray-700 rounded focus:ring-emerald-500 dark:focus:ring-emerald-500/20 cursor-pointer" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Parcelle viabilisée (eau, électricité, route d'accès)
                        </span>
                    </label>
                </div>

                <!-- Existing Images -->
                @if ($parcelle->images->isNotEmpty())
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Images existantes</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach ($parcelle->images as $image)
                                <div
                                    class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <img src="{{ $image->url }}" alt="Image {{ $loop->iteration }}"
                                        class="w-full h-32 object-cover" />
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-200 flex items-end">
                                        <div class="w-full p-2 bg-black/70 text-white text-xs space-y-1">
                                            @if ($image->principale)
                                                <span
                                                    class="block bg-emerald-600 px-2 py-1 rounded text-center font-medium">
                                                    Principale
                                                </span>
                                            @endif
                                            <button type="submit" form="delete-image-{{ $image->id }}"
                                                onclick="return confirm('Supprimer cette image ?')">
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add New Images -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ajouter des images</h2>
                    <x-form.file-input name="images" label="Images supplémentaires" accept="image/*"
                        multiple="{{ true }}" />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Formats acceptés : JPG, PNG, GIF, WEBP. Taille max : 5MB par image.
                    </p>
                </div>

                <!-- Action buttons -->
                <div
                    class="p-6 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex gap-3">
                        <x-btn style="outline" href="{{ route('parcelles.show', $parcelle) }}">
                            Annuler
                        </x-btn>

                        <x-btn type="submit">
                            <x-slot:prefix>
                                <i data-lucide="save"></i>
                            </x-slot:prefix>
                            Mettre à jour
                        </x-btn>
                    </div>
                </div>

            </form>

            <form id="delete-image-{{ $image->id }}" action="{{ route('parcelles.images.destroy', $image) }}"
                method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection
