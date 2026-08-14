@extends('layouts.dashboard')

@section('title', 'Modifier la catégorie de parcelle')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.configuration.parcelle-category.index') }}" class="text-primary dark:text-primary-400 text-xs font-medium mb-2 inline-block hover:text-primary-700 dark:hover:text-primary-300">
                &larr; Retour à la liste
            </a>
            <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Modifier la catégorie de parcelle</h1>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Mettez à jour les informations de la catégorie.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-950/30 border-l-4 border-red-500 dark:border-red-600 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="mt-1 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.configuration.parcelle-category.update', $category) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-form.input name="name" label="Nom de la catégorie *" placeholder="Ex: Terrain nu" value="{{ old('name', $category->name) }}" required />
                        <x-form.input name="slug" label="Slug (optionnel)" placeholder="Ex: terrain-nu" value="{{ old('slug', $category->slug) }}" />
                    </div>

                    <div>
                        <x-form.textarea name="description" label="Description (optionnelle)" rows="3" placeholder="Décrivez cette catégorie...">{{ old('description', $category->description) }}</x-form.textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-form.input name="sort_order" label="Ordre d'affichage" type="number" min="0" value="{{ old('sort_order', $category->sort_order) }}" />
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 text-emerald-600 border-gray-300 dark:border-gray-700 rounded focus:ring-emerald-500 dark:focus:ring-emerald-500/20 cursor-pointer" />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Catégorie active
                            </span>
                        </label>
                    </div>

                    <div class="flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700 pt-5">
                        <x-btn href="{{ route('admin.configuration.parcelle-category.index') }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Annuler
                        </x-btn>
                        <x-btn type="submit">
                            <x-slot:prefix>
                                <i data-lucide="check"></i>
                            </x-slot:prefix>
                            Mettre à jour
                        </x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection