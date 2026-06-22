<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-primary dark:bg-primary-700 text-white overflow-hidden">
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                    <i data-lucide="image" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white">Ajouter une réalisation</h1>
                    <p class="text-white/90 dark:text-white/90">Partagez vos travaux et projets avec la communauté</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('artisan.projects.store', $artisan) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ preview: null }">
                    @csrf

                    <!-- Section Informations -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
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
                                placeholder="Décrivez votre réalisation, les techniques utilisées, les matériaux..."
                                rows="4"
                            >{{ old('description') }}</x-form.textarea>

                            <!-- File input avec prévisualisation -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Photo du projet *
                                    <span class="text-red-500 dark:text-red-400">*</span>
                                </label>
                                
                                <div class="relative">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="image" class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 group">
                                            <template x-if="!preview">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="p-2 bg-gray-100 dark:bg-gray-600 rounded-full group-hover:bg-primary/10 dark:group-hover:bg-primary/20 transition-colors">
                                                        <i data-lucide="camera" class="w-8 h-8 text-gray-400 dark:text-gray-500 group-hover:text-primary dark:group-hover:text-primary-400 transition-colors"></i>
                                                    </div>
                                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="font-semibold text-primary dark:text-primary-400">Cliquez pour uploader</span> ou glissez-déposez
                                                    </p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">JPG, PNG, WebP (max. 5MB)</p>
                                                </div>
                                            </template>
                                            <template x-if="preview">
                                                <div class="relative w-full h-full">
                                                    <img :src="preview" class="w-full h-32 object-cover rounded-lg" alt="Aperçu">
                                                    <button type="button" @click="preview = null; document.getElementById('image').value = ''" class="absolute top-1 right-1 p-1 bg-red-500 dark:bg-red-600 text-white rounded-full hover:bg-red-600 dark:hover:bg-red-700">
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                </div>
                                            </template>
                                            <input type="file" id="image" name="image" accept="image/*" required class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                                        </label>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Conseils -->
                    <div class="bg-blue-50 dark:bg-blue-950/30 border-l-4 border-primary dark:border-primary-400 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary dark:text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-primary-800 dark:text-primary-300 font-medium mb-1">Conseils pour une belle réalisation :</p>
                                <ul class="text-sm text-primary-700 dark:text-primary-400 space-y-1">
                                    <li class="flex items-center gap-2">✓ Photo de bonne qualité, bien éclairée</li>
                                    <li class="flex items-center gap-2">✓ Montrez le résultat final de votre travail</li>
                                    <li class="flex items-center gap-2">✓ Ajoutez une description détaillée pour valoriser votre savoir-faire</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <x-btn href="{{ route('artisan.projects.index', $artisan) }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
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
</x-app-layout>