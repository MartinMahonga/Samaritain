@extends('layouts.owner')

@section('title', 'Signaler une intervention')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.interventions.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux interventions
    </a>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Signaler une intervention</h1>
</div>

<form action="{{ route('owner.interventions.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf

    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-primary"></i> Informations générales
            </h3>
            <x-form.input label="Titre de l'intervention" name="title" icon="type"
                placeholder="Fuite d'eau dans la cuisine" :value="old('title')" />
            <x-form.textarea label="Description détaillée" name="description"
                placeholder="Décrivez le problème, sa localisation et son impact..." />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i data-lucide="settings" class="w-4 h-4 text-primary"></i> Classification
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.select label="Catégorie" name="category" icon="tag"
                    placeholder="Sélectionner une catégorie"
                    :options="['plumbing' => 'Plomberie', 'painting' => 'Peinture', 'roofing' => 'Toiture', 'locksmith' => 'Serrurerie', 'garden' => 'Jardinage', 'heating' => 'Chauffage / Clim', 'appliances' => 'Électroménager', 'other' => 'Autre']" />
                <x-form.select label="Urgence" name="urgency" icon="alert-triangle"
                    placeholder="Niveau d'urgence"
                    :options="['low' => 'Faible', 'medium' => 'Moyenne', 'high' => 'Haute', 'emergency' => '🚨 Urgence']" />
                <x-form.input label="Coût estimé (FCFA)" name="cost" icon="banknote" type="number"
                    placeholder="0" :value="old('cost', 0)" />
                <x-form.input label="Date prévue" name="scheduled_at" icon="calendar" type="datetime-local"
                    :value="old('scheduled_at')" />
            </div>
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="is_renovation" id="is_renovation" value="1" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                    @checked(old('is_renovation'))>
                <label for="is_renovation" class="text-sm text-gray-700 dark:text-gray-300">Cocher si c'est une rénovation (investissement)</label>
            </div>
        </div>

        {{-- Photos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                <i data-lucide="image" class="w-4 h-4 text-primary"></i> Photos (optionnel)
            </h3>
            <input type="file" name="photos[]" accept="image/*" multiple
                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition" />
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">JPG, PNG — max 5 Mo par fichier</p>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-primary"></i> Bien & Artisan
            </h3>
            <x-form.select label="Propriété" name="property_id" icon="home"
                placeholder="Sélectionner un bien"
                :options="$properties->pluck('title', 'id')->toArray()" />

            @if($artisans->isNotEmpty())
                <x-form.select label="Artisan (optionnel)" name="artisan_id" icon="user"
                    placeholder="Affecter un artisan"
                    :options="$artisans->pluck('business_name', 'id')->toArray()" />
            @endif
        </div>

        @if($errors->any())
            <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-xl text-red-600 dark:text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition">
            <i data-lucide="send" class="w-4 h-4"></i>
            Enregistrer l'intervention
        </button>
    </div>
</form>
@endsection
