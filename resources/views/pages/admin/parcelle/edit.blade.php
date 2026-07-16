@extends('layouts.dashboard')

@section('title', 'Modifier une parcelle')

@section('content')
    <h1>Modifier la parcelle</h1>
    <x-container-dashed>
        <form action="{{ route('admin.parcelle.update', $parcelle) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <x-form.input name="titre" label="Titre de la parcelle" :value="$parcelle->titre" />
                </div>
                <x-form.input name="superficie" label="Superficie (m²)" type="number" step="0.01" :value="$parcelle->superficie" />
                <x-form.input name="prix" label="Prix" type="number" step="0.01" :value="$parcelle->prix" />
            </div>

            <x-form.textarea name="description" label="Description" :value="$parcelle->description" />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <x-form.input name="localisation" label="Localisation" :value="$parcelle->localisation" />
                <x-form.input name="quartier" label="Quartier" :value="$parcelle->quartier" />
                <x-form.input name="ville" label="Ville" :value="$parcelle->ville" />
              
            </div>

            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input name="titre_foncier" label="Titre foncier" :value="$parcelle->titre_foncier" />
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" name="viabilisee" id="viabilisee" value="1"
                        @checked($parcelle->viabilisee)
                        class="rounded border-gray-300 dark:border-gray-600 text-primary-600 shadow-sm focus:ring-primary-500 dark:bg-gray-700 dark:focus:ring-primary-400">
                    <label for="viabilisee" class="text-sm font-medium text-gray-700 dark:text-gray-300">Viabilisée</label>
                </div>
            </div> --}}

            {{-- Images existantes --}}
            @if ($parcelle->images->count() > 0)
                <div class="mb-3 flex flex-wrap gap-2 text-xs text-gray-700 font-medium">
                    @foreach ($parcelle->images as $image)
                        <div>
                            <img src="{{ $image->url }}" class="w-20 h-20 rounded-lg object-cover border">
                            <label class="flex items-center gap-1">
                                <input type="checkbox" name="kept_images[]" value="{{ $image->id }}" checked>
                                Conserver
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            <div>
                <x-form.file-input name="images" label="Images" accept="image/*" multiple="{{ true }}" />
            </div>

            <div class="flex items-center gap-3">
                <x-btn type="submit">
                    Modifier la parcelle
                </x-btn>
                <x-btn href="{{ route('admin.parcelle.index') }}" style="outline">
                    Annuler
                </x-btn>
            </div>
        </form>
    </x-container-dashed>
@endsection