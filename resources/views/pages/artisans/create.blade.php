<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-primary text-white overflow-hidden">
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                    <i data-lucide="drill" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold">Devenir artisan</h1>
                    <p class="text-white/90">Créez votre profil professionnel et rejoignez notre marketplace</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-800">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('artisan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- Section Informations générales -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            Informations générales
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input name="business_name" label="Nom de l'entreprise" placeholder="Ex: SARL BTP Congo" icon="building-2" required />
                            <x-form.input name="profession" label="Profession" placeholder="Ex: Maçon, Électricien, Plombier" icon="briefcase" required />
                        </div>
                    </div>

                    <!-- Section Contact -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            Contact
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input name="phone" label="Téléphone" type="tel" placeholder="06 12 34 56 78" icon="phone" required />
                            <x-form.input name="whatsapp" label="WhatsApp" type="tel" placeholder="06 12 34 56 78" icon="message-circle" />
                            <x-form.input name="website" label="Site web" type="url" placeholder="https://monsite.com" icon="globe" />
                            <x-form.input name="email" label="Email professionnel" type="email" placeholder="contact@entreprise.com" icon="mail" required />
                        </div>
                    </div>

                    <!-- Section Localisation -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            Localisation
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input name="city" label="Ville" placeholder="Brazzaville, Pointe-Noire..." icon="map-pin" required />
                            <x-form.input name="address" label="Adresse" placeholder="Quartier, rue..." icon="home" />
                        </div>
                    </div>

                    <!-- Section Expérience -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            Expérience
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form.input name="experience" label="Années d'expérience" type="number" placeholder="5" icon="award" />
                            <x-form.select name="team_size" label="Taille de l'équipe" placeholder="Sélectionnez" :options="[
                                '' => 'Sélectionnez...',
                                '1' => 'Indépendant',
                                '2-5' => '2-5 personnes',
                                '6-10' => '6-10 personnes',
                                '11-20' => '11-20 personnes',
                                '20+' => 'Plus de 20 personnes',
                            ]" icon="users" />
                        </div>
                    </div>

                    <!-- Section Spécialités -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            Spécialités
                        </h2>
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($categories as $category)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition-colors">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-primary focus:ring-primary/50">
                                        <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('categories')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section Description -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            Présentation
                        </h2>
                        <x-form.textarea name="bio" label="Description" placeholder="Présentez votre entreprise, vos services, votre savoir-faire..." rows="5" />
                    </div>

                    <!-- Section Médias -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            Photos
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-form.file-input name="avatar" label="Avatar" accept="image/*" />
                                <p class="mt-1 text-xs text-gray-500">Max 2 Mo. Formats : JPG, PNG, WebP</p>
                            </div>
                            <div>
                                <x-form.file-input name="cover" label="Photo de couverture" accept="image/*" />
                                <p class="mt-1 text-xs text-gray-500">Max 5 Mo. Formats : JPG, PNG, WebP</p>
                            </div>
                        </div>
                    </div>

                    <!-- Avertissement -->
                    <div class="bg-amber-50 border-l-4 border-amber-500 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-amber-800">
                                    Votre profil sera soumis à validation avant d'apparaître dans la marketplace.
                                    Vous serez notifié par email une fois votre compte vérifié.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                        <x-btn href="{{ route('artisans.index') }}" style="outline">
                            Annuler
                        </x-btn>
                        <x-btn type="submit" style="primary">
                            Créer mon profil artisan
                        </x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>