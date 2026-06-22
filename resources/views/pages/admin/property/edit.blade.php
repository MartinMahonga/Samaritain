@extends('layouts.dashboard')

@section('title', 'Modifier un bien')

@section('content')
    <h1>Modifier le bien</h1>
    <x-container-dashed>
        <form action="{{ route('admin.property.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="md:col-span-2">
                    <x-form.input name="title" label="Titre du bien" :value="$property->title" />
                </div>
                <x-form.input name="surface" label="Surface (m²)" type="number" step="0.01" :value="$property->surface" />
                <x-form.input name="price" label="Prix" type="number" step="0.01" :value="$property->price" />
                <x-form.select name="category_id" label="Catégorie" placeholder="Choisir une catégorie" :options="$categories" :value="$property->category_id" />
            </div>

            <x-form.textarea name="description" label="Description" :value="$property->description" />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-form.input name="rooms" label="Pièces" type="number" :value="$property->rooms" />
                <x-form.input name="bedrooms" label="Chambres" type="number" :value="$property->bedrooms" />
                <x-form.input name="bathrooms" label="Salle de bains" type="number" :value="$property->bathrooms" />
                <x-form.input name="floor" label="Étages" type="number" :value="$property->floor" />
            </div>

            <div>
                <x-form.multi-select name="amenities" label="Équipements" :options="$amenities" :value="$property->amenities->pluck('id')->toArray()" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-form.input name="address" label="Adresse" :value="$property->address" />
                <x-form.select name="city_id" label="Ville" placeholder="Choisir une ville" :options="$cities" :value="$property->city_id" />
                <x-form.select name="arrondissement_id" label="Arrondissement" placeholder="Choisir un arrondissement" :options="$arrondissements" :value="$property->arrondissement_id" />
                <x-form.select name="status" label="Statut" placeholder="Choisir un statut" :options="[
                    'available' => 'Disponible',
                    'sold' => 'Vendu',
                    'rented' => 'Loué',
                ]" :value="$property->status->value" />
            </div>

            {{-- IDs des images à conserver --}}
            <div class="mb-3 flex flex-wrap gap-2 text-xs text-gray-700 font-medium">
                @foreach ($property->images as $image)
                    <div>
                        <img src="{{ $image->image_url }}" class="w-20 h-20 rounded-lg object-cover border">
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="kept_images[]" value="{{ $image->id }}" checked>
                            Conserver
                        </label>
                    </div>
                @endforeach
            </div>

            <div>
                <x-form.file-input name="images" label="Images" accept="image/*" multiple="{{ true }}" />
            </div>

            <div class="flex items-center gap-3">
                <x-btn type="submit">
                    Modifier le bien
                </x-btn>
                <x-btn href="{{ route('admin.property.index') }}" style="outline">
                    Annuler
                </x-btn>
            </div>
        </form>
    </x-container-dashed>
@endsection