@extends('layouts.dashboard')

@section('title', 'Ajouter une parcelle')

@section('content')
    <h1>Créer une parcelle</h1>
    <x-container-dashed>
        <form action="{{ route('admin.parcelle.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <x-form.input name="titre" label="Titre de la parcelle" />
                </div>
                <x-form.input name="superficie" label="Superficie (m²)" type="number" step="0.01" />
                <x-form.input name="prix" label="Prix" type="number" step="0.01" />
            </div>

            <x-form.textarea name="description" label="Description" />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-form.input name="localisation" label="Localisation" />
                <x-form.input name="quartier" label="Quartier" />
                <x-form.input name="ville" label="Ville" />
                <x-form.select name="statut" label="Statut" placeholder="Choisir un statut" :options="[
                    'disponible' => 'Disponible',
                    'vendu' => 'Vendu',
                    'réservé' => 'Réservé',
                ]" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input name="titre_foncier" label="Titre foncier" />
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" name="viabilisee" id="viabilisee" value="1" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500 dark:bg-gray-700 dark:focus:ring-primary-400">
                    <label for="viabilisee" class="text-sm font-medium text-gray-700 dark:text-gray-300">Viabilisée</label>
                </div>
            </div>

            <div>
                <x-form.file-input name="images" label="Images" accept="image/*" multiple="{{ true }}" />
            </div>

            <div class="flex justify-end items-center gap-3">
                <x-btn href="{{ route('admin.parcelle.index') }}" style="outline">
                    Annuler
                </x-btn>
                <x-btn type="submit">
                    Enregistrer la parcelle
                </x-btn>
            </div>
        </form>
    </x-container-dashed>
@endsection