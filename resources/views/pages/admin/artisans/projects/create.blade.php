@extends('layouts.dashboard')

@section('title', 'Ajouter une réalisation')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.artisans.show', $artisan) }}"
                class="text-primary dark:text-primary-400 text-xs font-medium mb-2 inline-block hover:text-primary-700 dark:hover:text-primary-300">
                &larr; Retour au profil de {{ $artisan->business_name }}
            </a>
            <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Ajouter une réalisation</h1>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Ajoutez un projet ou travail réalisé par {{ $artisan->business_name }}.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.artisans.projects.store', $artisan) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- Détails du projet -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="folder" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Détails du projet
                        </h2>

                        <div class="space-y-5">
                            <x-form.input
                                name="title"
                                label="Titre du projet"
                                placeholder="Ex: Rénovation complète d'un appartement"
                                :value="old('title')"
                                icon="folder"
                                required
                            />

                            <x-form.textarea
                                name="description"
                                label="Description"
                                placeholder="Décrivez la réalisation, les techniques utilisées, les matériaux..."
                                rows="4"
                            >{{ old('description') }}</x-form.textarea>

                            <div>
                                <x-form.file-input name="images[]" label="Photos" multiple accept="image/*" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Vous pouvez sélectionner plusieurs photos. Max 5 Mo par image. Formats : JPG, PNG, WebP</p>
                            </div>
                        </div>
                    </div>

                    <!-- Conseils -->
                    <div class="bg-blue-50 dark:bg-blue-950/30 border-l-4 border-primary dark:border-primary-400 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-primary dark:text-primary-400 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-sm text-primary-800 dark:text-primary-300 font-medium mb-1">Conseils pour une belle réalisation :</p>
                                <ul class="text-sm text-primary-700 dark:text-primary-400 space-y-1">
                                    <li class="flex items-center gap-2">✓ Photos de bonne qualité, bien éclairées</li>
                                    <li class="flex items-center gap-2">✓ Montrez le résultat final du travail</li>
                                    <li class="flex items-center gap-2">✓ Ajoutez une description détaillée pour valoriser le savoir-faire</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <x-btn href="{{ route('admin.artisans.show', $artisan) }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Annuler
                        </x-btn>
                        <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                            <x-slot:prefix>
                                <i data-lucide="plus"></i>
                            </x-slot:prefix>
                            Ajouter la réalisation
                        </x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection