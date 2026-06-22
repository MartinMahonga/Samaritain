@extends('layouts.dashboard')

@section('title', 'Ajouter un bien')

@section('content')
    @php
        $status = [
            'available' => 'Disponible',
            'sold' => 'Vendu',
            'rented' => 'Loué',
        ];
    @endphp
    <h1>Créer un bien</h1>
    <x-container-dashed>
        <form action="{{ route('admin.property.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="md:col-span-2">
                    <x-form.input name="title" label="Titre du bien" />
                </div>
                <x-form.input name="surface" label="Surface (m²)" type="number" step="0.01" />
                <x-form.input name="price" label="Prix" type="number" step="0.01" />
                <x-form.select name="category_id" label="Catégorie" placeholder="Choisir une catégorie" :options="$categories" />
            </div>

            <x-form.textarea name="description" label="Description" />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-form.input name="rooms" label="Pièces" type="number" />
                <x-form.input name="bedrooms" label="Chambres" type="number" />
                <x-form.input name="bathrooms" label="Salle de bains" type="number" />
                <x-form.input name="floor" label="Étages" type="number" />
            </div>

            <div>
                <x-form.multi-select name="amenities" label="Équipements" :options="$amenities" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-form.input name="address" label="Adresse" />
                <x-form.select name="city_id" label="Ville" placeholder="Choisir une ville" :options="$cities" />
                <x-form.select name="arrondissement_id" label="Arrondissement" placeholder="Choisir un arrondissement" :options="$arrondissements" />
                <x-form.select name="status" label="Statut" placeholder="Choisir un statut" :options="[
                    'available' => 'Disponible',
                    'sold' => 'Vendu',
                    'rented' => 'Loué',
                ]" />
            </div>

            <div>
                <x-form.file-input name="images" label="Images" accept="image/*" multiple="{{ true }}" />
            </div>

            <div class="flex justify-end items-center gap-3">
                <x-btn href="{{ route('admin.property.index') }}" style="outline">
                    Annuler
                </x-btn>
                <x-btn type="submit">
                    Enregistrer le bien
                </x-btn>
            </div>
        </form>
    </x-container-dashed>
@endsection
