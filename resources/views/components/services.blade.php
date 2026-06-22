@php
    $services = [
        [
            'title' => 'Vente de biens',
            'description' => 'Accompagnement complet pour vendre rapidement votre propriété au meilleur prix.',
            'icon' => 'house',
        ],
        [
            'title' => 'Location immobilière',
            'description' => 'Mise en location, sélection des locataires et gestion administrative.',
            'icon' => 'key',
        ],
        [
            'title' => 'Gestion locative',
            'description' => 'Confiez-nous la gestion quotidienne de vos biens immobiliers.',
            'icon' => 'building',
        ],
        [
            'title' => 'Estimation immobilière',
            'description' => 'Évaluation précise de votre bien selon le marché actuel.',
            'icon' => 'chart-column',
        ],
    ];
@endphp

<section class="relative overflow-hidden px-6 py-10 lg:px-12 lg:py-16">
    <div class="grid gap-12 lg:grid-cols-2 lg:items-center">

        <!-- Partie droite -->
        <div class="space-y-8">

            <div>
                <span class="inline-flex items-center rounded-full text-xs border border-primary/20 bg-primary/10 px-4 py-1 font-medium text-primary">
                    Nos Services
                </span>

                <h2 class="mt-6 text-4xl font-bold font-display tracking-tight text-foreground md:text-5xl">
                    Des solutions immobilières
                    <span class="text-primary">sur mesure</span>
                </h2>

                <p class="mt-4 max-w-xl text-sm text-muted-foreground">
                    Nous accompagnons particuliers et professionnels dans toutes leurs démarches immobilières grâce à une expertise locale et un suivi personnalisé.
                </p>
            </div>

            <a
                href="#contact"
                class="inline-flex items-center gap-2 rounded-full bg-primary px-6 py-3 font-medium text-primary-foreground transition hover:opacity-90"
            >
                Nous contacter
                <i data-lucide="chevron-right"></i>
            </a>

            <div class="space-y-3 pt-4">
                @foreach ($services as $service)
                    <div class="flex items-center gap-4 rounded-xl p-4 hover:bg-muted/50 transition-colors">
                        <div class="flex h-10 w-18 md:h-10 md:w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <i data-lucide="{{ $service['icon'] }}" class="h-4 w-4 md:h-5 md:w-5"></i>
                        </div>

                        <div>
                            <h3 class="font-medium text-foreground">
                                {{ $service['title'] }}
                            </h3>

                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ $service['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>