@props(['faqs' => []])

<div x-data="{ openFaq: null }" class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold font-display text-center text-foreground mb-4">Questions fréquentes</h2>
            <p class="text-center text-sm text-muted-foreground mb-12">Tout ce que vous devez savoir avant de louer avec Samaritain.</p>

            <div class="space-y-4 border border-accent rounded-xl">
                @php
                    if(empty($faqs)) {
                        $faqs = [
                            (object) [
                                'id' => 1,
                                'question' => "Qu'est-ce que Samaritain ?",
                                'reponse' => "Samaritain est une plateforme immobilière innovante qui met en relation directe propriétaires et locataires, sans frais de commission. Nous facilitons la recherche de logements et la gestion locative."
                            ],
                            (object) [
                                'id' => 2,
                                'question' => "Pourquoi 0% de commission ?",
                                'reponse' => "Notre modèle économique repose sur la transparence et l'accessibilité. Nous croyons que louer un bien ne devrait pas être un luxe. Les propriétaires nous paient des services complémentaires, permettant aux locataires de bénéficier de 0% de commission."
                            ],
                            (object) [
                                'id' => 3,
                                'question' => "Vous êtes présents dans quelles villes ?",
                                'reponse' => "Nous sommes actuellement présents à Brazzaville, Pointe-Noire, et nous étendons prochainement notre couverture à d'autres grandes villes."
                            ],
                            (object) [
                                'id' => 4,
                                'question' => "Quels documents fournir ?",
                                'reponse' => "Pièce d'identité valide, justificatif de revenus (3 dernières fiches de paie), contrat de travail ou attestation d'emploi, et éventuellement un garant selon le profil."
                            ],
                            (object) [
                                'id' => 5,
                                'question' => "Puis-je visiter plusieurs biens ?",
                                'reponse' => "Absolument ! Vous pouvez visiter autant de biens que vous le souhaitez. Nos agents vous accompagnent pour organiser les visites selon vos disponibilités, sans aucun engagement."
                            ]
                        ];
                    }
                @endphp

                @foreach($faqs as $faq)
                    <div class="overflow-hidden">
                        <button 
                            @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}"
                            class="w-full px-6 py-4 text-left transition flex justify-between items-center"
                        >
                            <span class="font-semibold text-sm font-display text-foreground">{{ $faq->question }}</span>
                            <svg 
                                class="w-5 h-5 text-gray-500 transition-transform duration-200"
                                :class="{ 'rotate-180': openFaq === {{ $faq->id }} }"
                                fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="openFaq === {{ $faq->id }}"
                            x-collapse
                            class="px-6 py-4"
                        >
                            <p class="text-muted-foreground text-sm">{{ $faq->reponse }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>