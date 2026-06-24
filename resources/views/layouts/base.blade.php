<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Samaritain</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="openModal()" class="min-h-screen flex flex-col">
    <x-ui.navbar />

    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Bouton Visite rapide -->
    <button @click="isOpen=true"
        class="fixed flex items-center gap-2 bottom-28 md:right-8 right-4 px-4 py-2 rounded-4xl bg-primary text-white cursor-pointer z-50"
        aria-label="Demander une visite rapide">
        <i data-lucide="calendar-check" class="w-4 h-4"></i>
        <span class="text-sm">Visite rapide</span>
    </button>

    <x-ui.whatsapp-support-button />

    <!-- Modal Visite rapide -->
    <div x-cloak x-show="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70"
        @click.self="closeModal()">
        <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg m-3 md:m-0"
            @click.stop>
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 text-primary dark:text-primary-400">
                        <i data-lucide="calendar-check" class="w-5 h-5"></i>
                        <h2 class="font-display text-2xl dark:text-white">Visite rapide</h2>
                    </div>
                    <i data-lucide="x" @click="closeModal()"
                        class="w-4 h-4 cursor-pointer text-muted-foreground dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"></i>
                </div>
                <p class="text-muted-foreground dark:text-gray-400 text-sm">Laissez-nous vos coordonnées, nous vous
                    rappelons sous 5 minutes.</p>
            </div>
            <form id="visit-request-form" action="{{ route('visit-requests.store') }}" method="POST">
                @csrf
                <div class="flex flex-col gap-3">
                    <x-form.input label="Nom complet" name="full_name" icon="user" placeholder="Jean Dupont" />
                    <x-form.input label="Téléphone" name="phone" icon="phone" placeholder="06 800 71 38" />
                    <x-form.select label="Ville" name="city" icon="map-pin" placeholder="Sélectionnez une ville"
                        :options="[
                            'Brazzaville' => 'Brazzaville',
                            'Pointe-Noire' => 'Pointe-Noire',
                        ]" />
                    <x-form.select label="Bien souhaité" name="property_category" icon="home"
                        placeholder="Sélectionnez un bien" :options="[
                            'Studio' => 'Studio',
                            'Appartement' => 'Appartement',
                            'Villa' => 'Villa',
                            'Chambre salon' => 'Chambre salon',
                        ]" />
                    <x-form.select label="Créneau préféré" name="preferred_date" icon="clock"
                        placeholder="Choisissez un créneau" :options="[
                            'Matin (8h - 12h)' => 'Matin (8h - 12h)',
                            'Après-midi (13h - 17h)' => 'Après-midi (13h - 17h)',
                            'Soirée (17h - 19h)' => 'Soirée (17h - 19h)',
                            'Week-end' => 'Week-end',
                        ]" />
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <x-btn @click="closeModal()" style="outline"
                        class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                        Annuler
                    </x-btn>
                    <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                        <x-slot:prefix>
                            <i data-lucide="send"></i>
                        </x-slot:prefix>
                        Envoyer la demande
                    </x-btn>
                </div>
            </form>
        </div>
    </div>

    <x-ui.footer />
    <x-ui.mobile-nav />

    <script>
        function openModal() {
            return {
                isOpen: false,
                closeModal() {
                    this.isOpen = false;
                }
            }
        }

        // Gestion du formulaire avec Alpine.js et fetch
        document.addEventListener('alpine:init', () => {
            Alpine.data('visitRequestForm', () => ({
                isSubmitting: false,
                successMessage: '',
                errorMessage: '',

                async submitForm(event) {
                    event.preventDefault();
                    this.isSubmitting = true;
                    this.successMessage = '';
                    this.errorMessage = '';

                    const form = event.target;
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.successMessage = data.message || 'Demande envoyée avec succès !';
                            form.reset();
                            // Fermer la modal après 2 secondes
                            setTimeout(() => {
                                this.closeModal();
                                this.successMessage = '';
                            }, 2000);
                        } else {
                            this.errorMessage = data.message || 'Une erreur est survenue. Veuillez réessayer.';
                        }
                    } catch (error) {
                        this.errorMessage = 'Erreur de connexion. Veuillez réessayer.';
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }));
        });
    </script>
</body>

</html>