@extends('layouts.dashboard')

@section('title', 'Créer un Pass')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('passes.index') }}" class="text-primary dark:text-primary-400 text-xs font-medium mb-2 inline-block hover:text-primary-700 dark:hover:text-primary-300">
                &larr; Retour à la liste
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-gray-800 dark:text-white">Créer un nouveau Pass</h1>
        </div>

        <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <form action="{{ route('passes.store') }}" method="POST" x-data="passForm()" @submit="validateForm">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Colonne gauche -->
                        <div class="space-y-5">
                            <x-form.input label="Nom du titulaire" name="holder_name" x-model="holderName" />
                            <x-form.input label="Téléphone" type="tel" name="phone" icon="phone" />
                            <x-form.input label="Email(optionnel)" type="email" name="email" icon="mail" />
                            <x-form.input label="Nombre de visites autorisées" type="number" name="allowed_visits"
                                min="1" x-model="allowedVisits" @change="updateVisitsLabel" />

                            <div>
                                <div class="mt-2">
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        <span>Visites disponibles</span>
                                        <span x-text="allowedVisits"></span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1">
                                        <div class="bg-blue-600 dark:bg-blue-500 rounded-full h-1 transition-all duration-300"
                                            style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colonne droite -->
                        <div class="space-y-5">
                            <x-form.input label="Date de début" type="date" name="start_date" icon="calendar"
                                x-model="startDate" @change="updateExpirationMin" />
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Date d'expiration <span class="text-red-500 dark:text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="calendar-clock" class="h-4 w-4 text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <input type="date" name="expiration_date"
                                        value="{{ old('expiration_date', date('Y-m-d', strtotime('+7 days'))) }}" required
                                        x-model="expirationDate" :min="expirationMin"
                                        class="w-full h-9 rounded-lg text-sm border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-4 py-2 focus:outline-hidden focus:ring-2 focus:border-primary dark:focus:border-primary focus:ring-primary/10 dark:focus:ring-primary/20 pl-10">
                                </div>
                                <p class="flex gap-1 items-center text-xs text-gray-500 dark:text-gray-400 mt-1" x-show="durationDays">
                                    <i data-lucide="clock" class="w-4 h-4"></i> Durée: <span x-text="durationDays"></span> jour(s)
                                </p>
                                @error('expiration_date')
                                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Résumé -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 rounded-lg p-4 mt-4">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2 flex items-center gap-2">
                                    <i data-lucide="info" class="w-4 h-4"></i>
                                    Résumé du Pass
                                </h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Titulaire :</span>
                                        <span class="font-medium text-gray-800 dark:text-white" x-text="holderName || '—'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Visites :</span>
                                        <span class="font-medium text-gray-800 dark:text-white"
                                            x-text="allowedVisits + ' visite(s)'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Validité :</span>
                                        <span class="font-medium text-gray-800 dark:text-white"
                                            x-text="startDate + ' → ' + expirationDate"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <x-btn href="{{ route('passes.index') }}" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            <x-slot:prefix>
                                <i data-lucide="x"></i>
                            </x-slot:prefix>
                            Annuler
                        </x-btn>
                        <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                            <x-slot:prefix>
                                <i data-lucide="ticket-plus"></i>
                            </x-slot:prefix>
                            Créer le Pass
                        </x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function passForm() {
            return {
                holderName: '{{ old('holder_name') }}',
                allowedVisits: {{ old('allowed_visits', 3) }},
                startDate: '{{ old('start_date', date('Y-m-d')) }}',
                expirationDate: '{{ old('expiration_date', date('Y-m-d', strtotime('+7 days'))) }}',

                get expirationMin() {
                    return this.startDate;
                },

                get durationDays() {
                    if (this.startDate && this.expirationDate) {
                        const start = new Date(this.startDate);
                        const end = new Date(this.expirationDate);
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        return diffDays + 1;
                    }
                    return null;
                },

                updateExpirationMin() {
                    if (this.expirationDate < this.startDate) {
                        this.expirationDate = this.startDate;
                    }
                },

                updateVisitsLabel() {
                    // La mise à jour est automatique grâce à x-model
                },

                validateForm() {
                    // Validation supplémentaire si nécessaire
                    if (!this.startDate || !this.expirationDate) {
                        alert('Veuillez sélectionner les dates de validité');
                        return false;
                    }
                    return true;
                }
            }
        }
    </script>
@endsection