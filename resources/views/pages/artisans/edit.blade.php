<x-app-layout>
    <!-- Hero Section améliorée -->
    <div class="bg-primary dark:bg-primary-700 text-white overflow-hidden">
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                    <i data-lucide="drill" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white">Modifier mon profil</h1>
                    <p class="text-white/90 dark:text-white/90">Mettez à jour vos informations professionnelles</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('artisan.update', $artisan) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Section Informations générales -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            Informations générales
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input 
                                name="business_name" 
                                label="Nom de l'entreprise" 
                                placeholder="Ex: SARL BTP Congo"
                                :value="old('business_name', $artisan->business_name)" 
                                icon="building-2" 
                                required 
                            />
                            <x-form.input 
                                name="profession" 
                                label="Profession" 
                                placeholder="Ex: Maçon, Électricien, Plombier"
                                :value="old('profession', $artisan->profession)" 
                                icon="briefcase" 
                                required 
                            />
                        </div>
                    </div>

                    <!-- Section Contact -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            Contact
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input 
                                name="phone" 
                                label="Téléphone" 
                                type="tel" 
                                placeholder="06 12 34 56 78"
                                :value="old('phone', $artisan->phone)" 
                                icon="phone" 
                                required 
                            />
                            <x-form.input 
                                name="whatsapp" 
                                label="WhatsApp (optionnel)" 
                                type="tel" 
                                placeholder="06 12 34 56 78"
                                :value="old('whatsapp', $artisan->whatsapp)" 
                                icon="message-circle" 
                            />
                            <x-form.input 
                                name="website" 
                                label="Site web (optionnel)" 
                                type="url"
                                placeholder="https://monsite.com"
                                :value="old('website', $artisan->website)" 
                                icon="globe" 
                            />
                            <x-form.input 
                                name="email" 
                                label="Email professionnel (optionnel)" 
                                type="email"
                                placeholder="contact@entreprise.com"
                                :value="old('email', $artisan->email ?? auth()->user()->email ?? '')" 
                                icon="mail" 
                                required 
                            />
                        </div>
                    </div>

                    <!-- Section Localisation -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            Localisation
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @php
                                $cities = [
                                    1 => 'Brazzaville',
                                    2 => 'Pointe-Noire'
                                ]
                            @endphp
                            <x-form.select
                                name="city"
                                label="Ville"
                                :options="$cities"
                                placeholder="Choisir une ville"
                                :value="$artisan->city" 
                                icon="map-pin" 
                                required
                            />

                            <x-form.input 
                                name="address" 
                                label="Adresse" 
                                placeholder="Quartier, rue..."
                                :value="old('address', $artisan->address)" 
                                icon="home" 
                            />
                        </div>
                    </div>

                    <!-- Section Expérience -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            Expérience
                        </h2>
                        <div class="grid grid-cols-1 gap-5">
                            <x-form.input 
                                name="experience" 
                                label="Années d'expérience" 
                                type="number"
                                placeholder="5"
                                :value="old('experience', $artisan->experience)" 
                                icon="award" 
                            />
                        </div>
                    </div>

                    <!-- Section Spécialités -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            Spécialités
                        </h2>
                        <div class="border border-accent dark:border-gray-700 rounded-xl p-4 bg-sidebar dark:bg-gray-700/50">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($categories as $category)
                                    @php
                                        $selectedCategories = old('categories', $artisan->categories->pluck('id')->toArray());
                                    @endphp
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-accent dark:hover:bg-gray-700 p-2 rounded-lg transition-colors">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
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
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            Présentation
                        </h2>
                        <x-form.textarea 
                            name="bio" 
                            label="Description" 
                            placeholder="Présentez votre entreprise, vos services, votre savoir-faire..."
                            rows="5"
                            value="{{ $artisan->bio }}"
                        ></x-form.textarea>
                    </div>

                    <!-- Section Médias -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/35 dark:bg-primary/20 rounded-lg">
                                <svg class="w-4 h-4 text-primary dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            Photos
                        </h2>
                        
                        <!-- Avatar existant -->
                        @if ($artisan->avatar)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Avatar actuel</label>
                                <img src="{{ Storage::url($artisan->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif
                        
                        <!-- Cover existante -->
                        @if ($artisan->cover)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Photo de couverture actuelle</label>
                                <img src="{{ Storage::url($artisan->cover) }}" alt="Cover" class="w-full h-32 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.file-input 
                                name="avatar" 
                                label="Nouvel avatar" 
                                accept="image/*"
                                helper="Max 2 Mo. Formats : JPG, PNG, WebP. Laissez vide pour conserver l'actuel."
                            />
                            <x-form.file-input 
                                name="cover" 
                                label="Nouvelle photo de couverture" 
                                accept="image/*"
                                helper="Max 5 Mo. Formats : JPG, PNG, WebP. Laissez vide pour conserver l'actuelle."
                            />
                        </div>
                    </div>

                    <!-- Avertissement -->
                    <div class="bg-amber-50 dark:bg-amber-950/30 border-l-4 border-amber-500 dark:border-amber-600 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-amber-800 dark:text-amber-300">
                                    Après modification, votre profil sera à nouveau soumis à validation avant d'apparaître dans la marketplace.
                                    Vous serez notifié par email une fois votre compte vérifié.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <x-btn href="{{ route('artisan.dashboard') }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Annuler
                        </x-btn>
                        <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                            Mettre à jour mon profil
                        </x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.3; }
        }
        .animate-pulse {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</x-app-layout>