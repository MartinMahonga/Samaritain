{{-- <section class="max-w-7xl mx-auto px-6 py-16">

    <div class="text-center mb-12">
        <p class="text-primary text-sm font-semibold uppercase tracking-widest mb-2">Simple & rapide</p>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-300">Comment ça marche ?</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm max-w-md mx-auto">
            Trouvez votre logement en 3 étapes — sans commission, sans intermédiaire.
        </p>
    </div>

    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center mb-5 ring-4 ring-white">
                    <i data-lucide="search" class="w-8 h-8 text-primary"></i>
                </div>
                <span class="text-xs font-bold text-primary uppercase tracking-widest mb-2">Étape 1</span>
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-300 mb-2">Parcourez les biens</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Filtrez par quartier, superficie ou budget. Tous nos biens sont vérifiés et disponibles en temps
                    réel.
                </p>
            </div>

            <div class="flex flex-col items-center text-center">
                <div
                    class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center mb-5 ring-4 ring-white">
                    <i data-lucide="calendar-check" class="w-8 h-8 text-primary"></i>
                </div>
                <span class="text-xs font-bold text-primary uppercase tracking-widest mb-2">Étape 2</span>
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-300 mb-2">Planifiez une visite</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Contactez-nous directement. Votre dossier est pris en charge sous 24h et la visite organisée à votre
                    convenance.
                </p>
            </div>

            <div class="flex flex-col items-center text-center">
                <div
                    class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center mb-5 ring-4 ring-white">
                    <i data-lucide="key-round" class="w-8 h-8 text-primary"></i>
                </div>
                <span class="text-xs font-bold text-primary uppercase tracking-widest mb-2">Étape 3</span>
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-300 mb-2">Emménagez</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                    Signez votre contrat, récupérez vos clés. Zéro commission — vous ne payez que votre loyer.
                </p>
            </div>

        </div>
    </div>

</section> --}}

<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <p class="text-primary text-sm font-semibold uppercase tracking-widest mb-2">Simple & rapide</p>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-300">Commencez avec Samaritain <br>en
            trois étapes simples</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm max-w-md mx-auto">
            Trouvez votre logement en 3 étapes sans commission, sans intermédiaire.
        </p>
    </div>

    <div class="flex justify-between">
        <div>
            <div class="mb-10">
                <h2 class="flex items-center gap-4 font-bold text-xl">
                    <span class="text-sm">1</span>
                    <div class="flex items-center gap-2">
                        <i data-lucide="search" class="text-primary"></i>Parcourez les biens
                    </div>
                </h2>
                <div class="border-l-2 m-0.5">
                    <p class="w-96 text-sm px-6 py-3">Filtrez par quartier, superficie ou budget. Tous nos biens sont
                        vérifiés et disponibles en temps réel.</p>
                </div>
            </div>
            <div class="mb-10">
                <h2 class="flex items-center gap-4 font-bold text-xl">
                    <span class="text-sm">2</span>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar-check" class="text-primary"></i>Planifiez une visite
                    </div>
                </h2>
                <div class="border-l-2 m-0.5">
                    <p class="w-96 text-sm px-6 py-3">Contactez-nous directement. Votre dossier est pris en charge sous
                        24h et la visite organisée à votre convenance.</p>
                </div>
            </div>
            <div class="mb-10">
                <h2 class="flex items-center gap-4 font-bold text-xl">
                    <span class="text-sm">3</span>
                    <div class="flex items-center gap-2">
                        <i data-lucide="key-round" class="text-primary"></i>Emménagez
                    </div>
                </h2>
                <div class="border-l-2 m-0.5 px-6 py-3 flex flex-col">
                    <p class="w-96 text-sm">
                        Signez votre contrat, récupérez vos clés. Zéro commission vous ne
                        payez que votre loyer.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('property.index') }}"
                            class="bg-secondary dark:bg-primary text-white text-sm font-semibold px-4 py-2 rounded-full hover:opacity-90 transition">
                            Découvrir
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden md:block">
            <img src="{{ asset('artiste.jpg') }}" alt="" class="h-56">
        </div>
    </div>

</section>
