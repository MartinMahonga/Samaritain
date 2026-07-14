@extends('layouts.dashboard')

@section('title', 'Modifier un artisan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="mb-6">
            <a href="{{ route('admin.artisans.show', $artisan) }}"
                class="text-primary dark:text-primary-400 text-xs font-medium mb-2 inline-block hover:text-primary-700 dark:hover:text-primary-300">
                &larr; Retour au profil
            </a>
            <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Modifier l'artisan</h1>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Modifiez les informations professionnelles de {{ $artisan->business_name }}.</p>
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
                <form action="{{ route('admin.artisans.update', $artisan) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Section Informations générales -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="building-2" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Informations générales
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input name="business_name" label="Nom de l'entreprise" placeholder="Ex: SARL BTP Congo" icon="building-2" :value="old('business_name', $artisan->business_name)" required />
                            <x-form.input name="profession" label="Profession" placeholder="Ex: Maçon, Électricien, Plombier" icon="briefcase" :value="old('profession', $artisan->profession)" required />
                        </div>
                    </div>

                    <!-- Section Contact -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="phone" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Contact
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input name="phone" label="Téléphone" type="tel" placeholder="06 12 34 56 78" icon="phone" :value="old('phone', $artisan->phone)" required />
                            <x-form.input name="whatsapp" label="WhatsApp (optionnel)" type="tel" placeholder="06 12 34 56 78" icon="message-circle" :value="old('whatsapp', $artisan->whatsapp)" />
                            <x-form.input name="website" label="Site web (optionnel)" type="url" placeholder="https://monsite.com" icon="globe" :value="old('website', $artisan->website)" />
                        </div>
                    </div>

                    <!-- Section Localisation -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="map-pin" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Localisation
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input name="city" label="Ville" placeholder="Ex: Brazzaville" icon="map-pin" :value="old('city', $artisan->city)" required />
                        </div>
                    </div>

                    <!-- Section Expérience -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="award" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Expérience
                        </h2>
                        <div class="grid grid-cols-1 gap-5">
                            <x-form.input name="experience" label="Années d'expérience" type="number" placeholder="5" icon="award" :value="old('experience', $artisan->experience)" />
                        </div>
                    </div>

                    <!-- Section Spécialités -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="badge-check" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Spécialités
                        </h2>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 bg-gray-50 dark:bg-gray-700/50">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($categories as $category)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg transition-colors">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}
                                            class="rounded border-gray-300 dark:border-gray-600 text-primary dark:text-primary-400 focus:ring-primary/50 dark:focus:ring-primary/30 dark:bg-gray-800">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('categories')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section Description -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="align-left" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Présentation
                        </h2>
                        <x-form.textarea name="bio" label="Description" placeholder="Présentez l'entreprise, ses services, son savoir-faire..." rows="5">{{ old('bio', $artisan->bio) }}</x-form.textarea>
                    </div>

                    <!-- Section Médias -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 dark:bg-primary/20 rounded-lg">
                                <i data-lucide="image" class="w-4 h-4 text-primary dark:text-primary-400"></i>
                            </div>
                            Photos
                        </h2>

                        @if ($artisan->avatar)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Avatar actuel</label>
                                <img src="{{ Storage::url($artisan->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif

                        @if ($artisan->cover)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photo de couverture actuelle</label>
                                <img src="{{ Storage::url($artisan->cover) }}" alt="Cover" class="w-full h-32 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-form.file-input name="avatar" label="Nouveau avatar" accept="image/*" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 2 Mo. Formats : JPG, PNG, WebP. Laissez vide pour conserver l'actuel.</p>
                            </div>
                            <div>
                                <x-form.file-input name="cover" label="Nouvelle photo de couverture" accept="image/*" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 5 Mo. Formats : JPG, PNG, WebP. Laissez vide pour conserver l'actuelle.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <x-btn href="{{ route('admin.artisans.show', $artisan) }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Annuler
                        </x-btn>
                        <x-btn type="submit" style="primary" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                            <x-slot:prefix>
                                <i data-lucide="save" class="w-4 h-4"></i>
                            </x-slot:prefix>
                            Mettre à jour le profil
                        </x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection