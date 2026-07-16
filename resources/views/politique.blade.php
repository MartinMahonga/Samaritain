@extends('layouts.base')
@section('title', 'politique')

@section('content')

    <section>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Politique de Confidentialité — Samaritain</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link
            href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;700;800;900&family=Inter:wght@400;500;600;700&display=swap"
            rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/3.4.1/tailwind.min.js"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            display: ['Archivo', 'sans-serif'],
                            body: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            ink: '#1A1613',
                            orange: { DEFAULT: '#E8630A', dark: '#B84D08', light: '#FCE7D6' },
                            paper: '#FFFDF9',
                        },
                    },
                },
            }
        </script>
        <style>
            .blueprint-bg {
                background-image:
                    linear-gradient(rgba(26, 22, 19, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(26, 22, 19, 0.05) 1px, transparent 1px);
                background-size: 28px 28px;
            }

            html {
                scroll-behavior: smooth;
            }

            .article-anchor {
                scroll-margin-top: 6.5rem;
            }

            .toc-link.active {
                color: #E8630A;
                font-weight: 700;
            }

            .toc-link.active::before {
                opacity: 1;
            }

            .toc-link::before {
                content: '';
                position: absolute;
                left: -13px;
                top: 50%;
                transform: translateY(-50%);
                width: 6px;
                height: 6px;
                border-radius: 9999px;
                background: #E8630A;
                opacity: 0;
            }
        </style>

        <!-- COVER -->
        <section class="blueprint-bg border-b border-ink/10">
            <div class="max-w-7xl mx-auto px-6 py-16 md:py-20">
                <p class="italic text-ink/60 text-sm mb-3">Vivez sereinement.</p>
                <h1 class="font-display font-black text-4xl md:text-5xl tracking-tight">Politique de Confidentialité</h1>
                <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-sm bg-ink text-paper font-semibold">Version
                        1.0</span>
                    <span class="text-ink/50">Dernière mise à jour&nbsp;: 11 juillet 2026</span>
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-sm bg-orange-light text-orange-dark font-semibold text-xs">Loi
                        n° 29-2019 — RC</span>
                </div>
                <div class="mt-8 max-w-2xl border-l-2 border-orange pl-5 py-1">
                    <p class="text-sm text-ink/60 italic leading-relaxed">
                        Document préparé à titre de base de travail, établi à la lumière de la loi n° 29-2019 du 10 octobre
                        2019 portant protection des données à caractère personnel en République du Congo. Il est fortement
                        recommandé de le faire valider par un avocat ou juriste compétent avant toute mise en ligne,
                        notamment pour finaliser l'identité du responsable du traitement une fois la structure juridique de
                        Samaritain immatriculée, et pour confirmer les modalités pratiques de déclaration auprès de
                        l'autorité compétente.
                    </p>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-6 py-14 grid lg:grid-cols-[260px_1fr] gap-14">

            <!-- TOC -->
            <nav aria-label="Sommaire" class="hidden lg:block">
                <div class="sticky top-24">
                    <p class="text-xs font-bold tracking-[0.2em] uppercase text-ink/40 mb-4">Sommaire</p>
                    <ul class="space-y-2.5 text-sm border-l border-ink/10 pl-5">
                        <li><a href="#s1" class="toc-link relative text-ink/60 hover:text-ink transition-colors">1 —
                                Préambule</a></li>
                        <li><a href="#s2" class="toc-link relative text-ink/60 hover:text-ink transition-colors">2 —
                                Responsable du traitement</a></li>
                        <li><a href="#s3" class="toc-link relative text-ink/60 hover:text-ink transition-colors">3 — Données
                                collectées</a></li>
                        <li><a href="#s4" class="toc-link relative text-ink/60 hover:text-ink transition-colors">4 —
                                Finalités</a></li>
                        <li><a href="#s5" class="toc-link relative text-ink/60 hover:text-ink transition-colors">5 — Base
                                légale</a></li>
                        <li><a href="#s6" class="toc-link relative text-ink/60 hover:text-ink transition-colors">6 —
                                Destinataires</a></li>
                        <li><a href="#s7" class="toc-link relative text-ink/60 hover:text-ink transition-colors">7 —
                                Transferts hors CEMAC</a></li>
                        <li><a href="#s8" class="toc-link relative text-ink/60 hover:text-ink transition-colors">8 — Durée
                                de conservation</a></li>
                        <li><a href="#s9" class="toc-link relative text-ink/60 hover:text-ink transition-colors">9 —
                                Sécurité des données</a></li>
                        <li><a href="#s10" class="toc-link relative text-ink/60 hover:text-ink transition-colors">10 — Vos
                                droits</a></li>
                        <li><a href="#s11" class="toc-link relative text-ink/60 hover:text-ink transition-colors">11 —
                                Utilisateurs mineurs</a></li>
                        <li><a href="#s12" class="toc-link relative text-ink/60 hover:text-ink transition-colors">12 —
                                Violation de données</a></li>
                        <li><a href="#s13" class="toc-link relative text-ink/60 hover:text-ink transition-colors">13 —
                                Déclaration autorité</a></li>
                        <li><a href="#s14" class="toc-link relative text-ink/60 hover:text-ink transition-colors">14 —
                                Cookies &amp; traceurs</a></li>
                        <li><a href="#s15" class="toc-link relative text-ink/60 hover:text-ink transition-colors">15 —
                                Contact &amp; modification</a></li>
                    </ul>
                </div>
            </nav>

            <!-- SECTIONS -->
            <main class="min-w-0 space-y-16">

                <article id="s1" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">01</span> Préambule
                    </h2>
                    <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
                        <p>Samaritain (« nous », « la Plateforme ») accorde une importance particulière à la protection de
                            la vie privée et des données à caractère personnel de ses utilisateurs (« vous », «
                            l'Utilisateur »). La présente Politique de confidentialité complète les Conditions Générales
                            d'Utilisation (CGU) de la Plateforme et décrit la manière dont vos données sont collectées,
                            utilisées, conservées et protégées.</p>
                        <p>Cette politique est établie conformément à la loi n° 29-2019 du 10 octobre 2019 portant
                            protection des données à caractère personnel de la République du Congo (ci-après la « Loi »),
                            qui encadre tout traitement de données à caractère personnel mis en œuvre sur le territoire
                            congolais ou par un responsable de traitement qui y recourt à des moyens de traitement.</p>
                    </div>
                </article>

                <article id="s2" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">02</span> Responsable du traitement
                    </h2>
                    <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
                        <p>Le responsable du traitement des données à caractère personnel collectées via la Plateforme est
                            Samaritain, <span class="italic text-ink/55">[dénomination sociale complète et forme juridique à
                                préciser une fois l'immatriculation finalisée]</span>, dont le siège social est situé à
                            Brazzaville, République du Congo.</p>
                        <p>Pour toute question relative au traitement de vos données personnelles, vous pouvez contacter
                            Samaritain via les canaux indiqués à l'<a href="#s15"
                                class="text-orange-dark font-semibold hover:underline">Article 15</a> de la présente
                            politique.</p>
                    </div>
                </article>

                <article id="s3" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">03</span> Données que nous collectons
                    </h2>
                    <p class="mt-4 text-ink/70 leading-relaxed max-w-3xl">Selon les modules de la Plateforme que vous
                        utilisez, les catégories de données suivantes peuvent être collectées&nbsp;:</p>

                    <div class="mt-5 overflow-x-auto rounded-sm border border-ink/10 max-w-3xl">
                        <table class="w-full text-sm min-w-[560px]">
                            <thead>
                                <tr class="bg-ink text-paper">
                                    <th class="text-left font-display font-bold px-4 py-3">Catégorie</th>
                                    <th class="text-left font-display font-bold px-4 py-3">Exemples de données</th>
                                    <th class="text-left font-display font-bold px-4 py-3">Modules concernés</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ink/10">
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Identification</td>
                                    <td class="px-4 py-3 text-ink/70">Nom, prénom, date de naissance, pièce d'identité</td>
                                    <td class="px-4 py-3 text-ink/70">Tous modules à vérification</td>
                                </tr>
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Contact</td>
                                    <td class="px-4 py-3 text-ink/70">Numéro de téléphone, adresse e-mail</td>
                                    <td class="px-4 py-3 text-ink/70">Tous modules</td>
                                </tr>
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Vérification foncière</td>
                                    <td class="px-4 py-3 text-ink/70">Titre de propriété, certificat d'achat</td>
                                    <td class="px-4 py-3 text-ink/70">Vente de terrains / parcelles</td>
                                </tr>
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Localisation</td>
                                    <td class="px-4 py-3 text-ink/70">Adresse du bien, quartier, coordonnées</td>
                                    <td class="px-4 py-3 text-ink/70">Location, vente, artisanat</td>
                                </tr>
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Financières</td>
                                    <td class="px-4 py-3 text-ink/70">Historique de transactions, montants, moyens de
                                        paiement</td>
                                    <td class="px-4 py-3 text-ink/70">Pass visite, abonnement, commission, Tontine</td>
                                </tr>
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Professionnelles</td>
                                    <td class="px-4 py-3 text-ink/70">Métier, zone d'intervention, avis clients</td>
                                    <td class="px-4 py-3 text-ink/70">Artisanat</td>
                                </tr>
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Techniques</td>
                                    <td class="px-4 py-3 text-ink/70">Adresse IP, identifiants de connexion, journaux
                                        d'activité</td>
                                    <td class="px-4 py-3 text-ink/70">Tous modules</td>
                                </tr>
                                <tr class="odd:bg-orange-light/30">
                                    <td class="px-4 py-3 font-semibold text-ink">Signature électronique</td>
                                    <td class="px-4 py-3 text-ink/70">Code de vérification, empreinte cryptographique du
                                        document</td>
                                    <td class="px-4 py-3 text-ink/70">Signature électronique</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 max-w-3xl bg-ink text-paper rounded-sm p-5 flex gap-3">
                        <span class="text-orange text-lg leading-none">🛈</span>
                        <p class="text-sm text-paper/85 leading-relaxed">Conformément à l'Article 14 de la Loi, Samaritain
                            ne collecte ni ne traite les catégories particulières de données (origine ethnique ou régionale,
                            filiation, opinions politiques, convictions religieuses ou philosophiques, appartenance
                            syndicale, vie sexuelle, données génétiques, données de santé), sauf obligation légale
                            contraire.</p>
                    </div>
                </article>

                <article id="s4" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">04</span> Finalités du traitement
                    </h2>
                    <p class="mt-4 text-ink/70 leading-relaxed max-w-3xl">Vos données sont traitées pour les finalités
                        suivantes&nbsp;:</p>
                    <ul class="mt-5 space-y-2.5 max-w-3xl">
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Créer et
                            gérer votre compte utilisateur</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Publier et
                            gérer vos annonces (location, vente de terrains et parcelles)</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Vous mettre
                            en relation avec d'autres utilisateurs (artisans, propriétaires, locataires, acheteurs)</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Vérifier
                            l'identité et la qualité de propriétaire, conformément à l'Article 7 des CGU</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Organiser et
                            encadrer les visites de biens par un Agent Samaritain</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Traiter les
                            paiements (Pass visite, abonnement Artisan, commission de vente)</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Assurer la
                            signature électronique de documents et garantir leur intégrité</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Vous mettre
                            en relation avec nos notaires partenaires, à votre demande, dans le cadre d'une régularisation
                            de titre</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Assurer la
                            sécurité de la Plateforme et prévenir la fraude</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Respecter nos
                            obligations légales et réglementaires</li>
                    </ul>
                </article>

                <article id="s5" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">05</span> Base légale du traitement
                    </h2>
                    <p class="mt-4 text-ink/70 leading-relaxed max-w-3xl">Conformément à l'Article 5 de la Loi, le
                        traitement de vos données repose, selon les cas, sur&nbsp;:</p>
                    <div class="mt-5 grid sm:grid-cols-2 gap-px bg-ink/10 border border-ink/10 max-w-3xl">
                        <div class="bg-paper p-5">
                            <p class="text-sm text-ink/75 leading-relaxed">Votre <span
                                    class="font-semibold text-ink">consentement</span>, notamment lors de la création de
                                votre compte et de l'acceptation des CGU</p>
                        </div>
                        <div class="bg-paper p-5">
                            <p class="text-sm text-ink/75 leading-relaxed">L'<span class="font-semibold text-ink">exécution
                                    du contrat</span> vous liant à Samaritain (fourniture des services de la Plateforme)</p>
                        </div>
                        <div class="bg-paper p-5">
                            <p class="text-sm text-ink/75 leading-relaxed">Le respect d'une <span
                                    class="font-semibold text-ink">obligation légale</span> à laquelle Samaritain est
                                soumise (par exemple en matière de lutte contre la fraude foncière)</p>
                        </div>
                        <div class="bg-paper p-5">
                            <p class="text-sm text-ink/75 leading-relaxed">La <span
                                    class="font-semibold text-ink">sauvegarde de vos intérêts</span> ou de ceux d'un tiers,
                                dans les cas prévus par la Loi</p>
                        </div>
                    </div>
                </article>

                <article id="s6" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">06</span> Destinataires des données
                    </h2>
                    <p class="mt-4 text-ink/70 leading-relaxed max-w-3xl">Vos données peuvent être communiquées, dans la
                        limite de ce qui est nécessaire à chaque finalité, aux catégories de destinataires suivantes&nbsp;:
                    </p>
                    <ul class="mt-5 space-y-2.5 max-w-3xl">
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Le personnel
                            habilité de Samaritain, dans le cadre de ses fonctions</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Les Agents
                            Samaritain chargés d'encadrer les visites de biens</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Les autres
                            Utilisateurs, dans la stricte mesure nécessaire à une mise en relation (par exemple, coordonnées
                            communiquées à un Propriétaire par un Acheteur intéressé)</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Nos
                            prestataires techniques (hébergement, paiement, envoi de SMS), agissant en qualité de
                            sous-traitants conformément à l'Article 11 de la Loi et liés à Samaritain par un contrat écrit
                        </li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Nos notaires
                            partenaires, uniquement à votre demande expresse, dans le cadre d'une régularisation de titre
                            foncier</li>
                        <li class="flex gap-3 text-sm text-ink/75"><span class="text-orange shrink-0">—</span> Les autorités
                            administratives ou judiciaires compétentes, lorsque la loi l'exige</li>
                    </ul>
                    <div class="mt-5 max-w-3xl border-l-4 border-orange bg-orange-light/40 rounded-r-sm p-5">
                        <p class="text-sm font-semibold text-ink">Samaritain ne vend ni ne loue vos données personnelles à
                            des tiers à des fins commerciales.</p>
                    </div>
                </article>

                <article id="s7" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">07</span> Transferts de données hors de la zone CEMAC
                    </h2>
                    <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
                        <p>Certaines données peuvent être hébergées ou traitées par des prestataires techniques situés hors
                            de la zone CEMAC/CEEAC, qualifiée de « pays tiers » au sens de l'Article 4 de la Loi (par
                            exemple, hébergement infonuagique). Conformément aux Articles 23 et 24 de la Loi, un tel
                            transfert n'a lieu que si le pays concerné assure un niveau de protection suffisant de la vie
                            privée, ou si le transfert répond à l'une des conditions prévues par la Loi (transfert ponctuel
                            et non massif avec consentement, exécution du contrat, sauvegarde d'un intérêt vital).</p>
                        <p>Conformément à l'Article 23 de la Loi, Samaritain informe l'autorité compétente de tout transfert
                            de données à caractère personnel vers un pays tiers dès lors qu'un tel transfert est mis en
                            œuvre de façon récurrente.</p>
                    </div>
                </article>

                <article id="s8" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">08</span> Durée de conservation
                    </h2>
                    <p class="mt-4 text-ink/70 leading-relaxed max-w-3xl">Conformément à l'Article 7 de la Loi, vos données
                        sont conservées pendant la durée nécessaire aux finalités pour lesquelles elles ont été
                        collectées&nbsp;:</p>
                    <div class="mt-5 grid sm:grid-cols-2 gap-px bg-ink/10 border border-ink/10 max-w-3xl">
                        <div class="bg-paper p-5">
                            <h3 class="font-display font-bold text-sm">Données de compte</h3>
                            <p class="mt-1.5 text-sm text-ink/70 leading-relaxed">Pendant toute la durée d'utilisation de la
                                Plateforme, puis archivées pendant la durée nécessaire au respect de nos obligations
                                légales.</p>
                        </div>
                        <div class="bg-paper p-5">
                            <h3 class="font-display font-bold text-sm">Documents de vérification</h3>
                            <p class="mt-1.5 text-sm text-ink/70 leading-relaxed">Pièce d'identité, titre de
                                propriété&nbsp;: pendant la durée strictement nécessaire à la vérification, puis archivés
                                selon les délais requis en matière foncière et de lutte contre la fraude.</p>
                        </div>
                        <div class="bg-paper p-5">
                            <h3 class="font-display font-bold text-sm">Données de transaction</h3>
                            <p class="mt-1.5 text-sm text-ink/70 leading-relaxed">Pass visite, abonnement, commission&nbsp;:
                                pendant la durée nécessaire aux obligations comptables et fiscales applicables.</p>
                        </div>
                        <div class="bg-paper p-5">
                            <h3 class="font-display font-bold text-sm">Signature électronique</h3>
                            <p class="mt-1.5 text-sm text-ink/70 leading-relaxed">Documents conservés dans les conditions
                                garantissant leur valeur probatoire, pendant la durée applicable au type de document
                                concerné.</p>
                        </div>
                    </div>
                    <p class="mt-5 text-sm text-ink/60 italic max-w-3xl">Au-delà de ces durées, vos données sont supprimées
                        ou anonymisées, sauf obligation légale de conservation plus longue.</p>
                </article>

                <article id="s9" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">09</span> Sécurité des données
                    </h2>
                    <p class="mt-4 text-ink/70 leading-relaxed max-w-3xl">Conformément aux Articles 63, 64 et 71 de la Loi,
                        Samaritain met en œuvre les mesures techniques et organisationnelles appropriées pour protéger vos
                        données contre la perte, l'accès non autorisé, la divulgation, l'altération ou la destruction,
                        notamment&nbsp;:</p>
                    <div class="mt-5 grid sm:grid-cols-2 gap-4 max-w-3xl">
                        <div class="border border-ink/15 rounded-sm p-4 flex items-start gap-3">
                            <span class="text-orange shrink-0">🔒</span>
                            <span class="text-sm text-ink/75">Chiffrement des documents sensibles (verrouillage
                                cryptographique SHA-256 pour les documents signés électroniquement)</span>
                        </div>
                        <div class="border border-ink/15 rounded-sm p-4 flex items-start gap-3">
                            <span class="text-orange shrink-0">🔑</span>
                            <span class="text-sm text-ink/75">Contrôle des accès&nbsp;: seules les personnes habilitées,
                                agissant sur instruction de Samaritain, peuvent accéder aux données</span>
                        </div>
                        <div class="border border-ink/15 rounded-sm p-4 flex items-start gap-3">
                            <span class="text-orange shrink-0">📱</span>
                            <span class="text-sm text-ink/75">Vérification par code à usage unique envoyé par SMS pour les
                                opérations sensibles</span>
                        </div>
                        <div class="border border-ink/15 rounded-sm p-4 flex items-start gap-3">
                            <span class="text-orange shrink-0">💾</span>
                            <span class="text-sm text-ink/75">Sauvegardes régulières des données</span>
                        </div>
                        <div class="border border-ink/15 rounded-sm p-4 flex items-start gap-3 sm:col-span-2">
                            <span class="text-orange shrink-0">📝</span>
                            <span class="text-sm text-ink/75">Engagement contractuel de confidentialité de toute personne ou
                                sous-traitant intervenant sur les données</span>
                        </div>
                    </div>
                </article>

                <article id="s10" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">10</span> Vos droits
                    </h2>
                    <p class="mt-4 text-ink/70 leading-relaxed max-w-3xl">Conformément au Titre III de la Loi, vous disposez
                        des droits suivants sur vos données à caractère personnel&nbsp;:</p>

                    <dl class="mt-5 divide-y divide-ink/10 border-t border-b border-ink/10 max-w-3xl">
                        <div class="grid sm:grid-cols-[13rem_1fr] gap-1.5 sm:gap-6 py-4">
                            <dt class="font-display font-bold text-sm">Droit à l'information <span
                                    class="text-orange-dark font-normal">(Art. 46)</span></dt>
                            <dd class="text-ink/70 text-sm leading-relaxed">Être informé, de façon claire, des finalités et
                                destinataires du traitement de vos données.</dd>
                        </div>
                        <div class="grid sm:grid-cols-[13rem_1fr] gap-1.5 sm:gap-6 py-4">
                            <dt class="font-display font-bold text-sm">Droit d'accès <span
                                    class="text-orange-dark font-normal">(Art. 50-51)</span></dt>
                            <dd class="text-ink/70 text-sm leading-relaxed">Obtenir la confirmation que vos données sont
                                traitées et en obtenir une copie.</dd>
                        </div>
                        <div class="grid sm:grid-cols-[13rem_1fr] gap-1.5 sm:gap-6 py-4">
                            <dt class="font-display font-bold text-sm">Rectification &amp; suppression <span
                                    class="text-orange-dark font-normal">(Art. 60)</span></dt>
                            <dd class="text-ink/70 text-sm leading-relaxed">Faire corriger, compléter ou supprimer des
                                données inexactes, incomplètes ou périmées.</dd>
                        </div>
                        <div class="grid sm:grid-cols-[13rem_1fr] gap-1.5 sm:gap-6 py-4">
                            <dt class="font-display font-bold text-sm">Droit d'opposition <span
                                    class="text-orange-dark font-normal">(Art. 59)</span></dt>
                            <dd class="text-ink/70 text-sm leading-relaxed">Vous opposer, pour un motif légitime, à un
                                traitement de vos données.</dd>
                        </div>
                        <div class="grid sm:grid-cols-[13rem_1fr] gap-1.5 sm:gap-6 py-4">
                            <dt class="font-display font-bold text-sm">Droit à la portabilité <span
                                    class="text-orange-dark font-normal">(Art. 56-57)</span></dt>
                            <dd class="text-ink/70 text-sm leading-relaxed">Recevoir vos données dans un format structuré et
                                couramment utilisé, lorsque le traitement est automatisé et fondé sur votre consentement.
                            </dd>
                        </div>
                    </dl>

                    <p class="mt-5 text-sm text-ink/70 leading-relaxed max-w-3xl">Vous pouvez exercer ces droits en
                        adressant une demande écrite à Samaritain, en justifiant de votre identité, via les canaux indiqués
                        à l'Article 15. Conformément à l'Article 60 de la Loi, Samaritain s'engage à répondre dans un délai
                        d'un mois à compter de l'enregistrement de la demande, sans frais pour vous, sauf demande
                        manifestement abusive.</p>
                </article>

                <article id="s11" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">11</span> Utilisateurs mineurs
                    </h2>
                    <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
                        <p>Conformément à l'Article 62 de la Loi, une personne mineure âgée d'au moins seize (16) ans peut
                            consentir seule à un traitement de données dans le cadre de l'offre de services de la société de
                            l'information. En deçà de cet âge, le traitement n'est licite que si le consentement est donné
                            conjointement par le mineur et le ou les titulaires de l'autorité parentale.</p>
                        <p>La conclusion de transactions immobilières, financières ou de prestations artisanales via la
                            Plateforme suppose en tout état de cause la capacité juridique à contracter, conformément aux
                            règles de droit commun applicables à la majorité civile.</p>
                    </div>
                </article>

                <article id="s12" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">12</span> Violation de données à caractère personnel
                    </h2>
                    <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">En cas de violation de données à caractère
                        personnel susceptible d'engendrer un risque pour vos droits et libertés, Samaritain s'engage,
                        conformément aux Articles 74 à 78 de la Loi, à notifier cette violation à l'autorité compétente dans
                        les meilleurs délais et, si possible, dans un délai de <span
                            class="font-semibold text-ink">soixante-douze heures</span>, et à vous en informer directement
                        lorsque cette violation est susceptible d'engendrer un risque élevé pour vos droits et libertés.</p>
                </article>

                <article id="s13" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">13</span> Déclaration auprès de l'autorité compétente
                    </h2>
                    <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">Conformément à l'Article 33 de la Loi, les
                        traitements de données à caractère personnel mis en œuvre par Samaritain font l'objet d'une
                        déclaration auprès de la commission chargée de la protection des données à caractère personnel,
                        préalablement à leur mise en œuvre.</p>
                </article>

                <article id="s14" class="article-anchor">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">14</span> Cookies et traceurs
                    </h2>
                    <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">Lorsque vous utilisez le site internet de la
                        Plateforme, des cookies ou traceurs techniques peuvent être utilisés pour assurer son bon
                        fonctionnement. Conformément à l'Article 49 de la Loi, vous êtes informé de la finalité de tout
                        dispositif de ce type ainsi que des moyens dont vous disposez pour vous y opposer, sauf lorsque ce
                        dispositif est strictement nécessaire à la fourniture du service que vous avez demandé.</p>
                </article>

                <article id="s15" class="article-anchor pb-4">
                    <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
                        <span class="text-orange">15</span> Contact et modification de la politique
                    </h2>
                    <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
                        <p>Pour toute question relative à la présente Politique de confidentialité ou pour exercer vos
                            droits, vous pouvez contacter Samaritain via les canaux de contact indiqués sur la Plateforme.
                        </p>
                        <p>Samaritain peut être amenée à modifier la présente politique, notamment pour tenir compte
                            d'évolutions légales ou techniques. Toute modification substantielle vous sera communiquée avant
                            son entrée en vigueur.</p>
                    </div>
                </article>

                <div class="pt-10 border-t border-ink/10 text-center">
                    <p class="italic text-ink/50 text-sm">Fin de la Politique de confidentialité — Samaritain, « Vivez
                        sereinement. »</p>
                </div>

            </main>
        </div>


        <script>
            const links = document.querySelectorAll('.toc-link');
            const sections = document.querySelectorAll('.article-anchor');
            const map = new Map();
            links.forEach(l => map.set(l.getAttribute('href').slice(1), l));

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const link = map.get(entry.target.id);
                    if (!link) return;
                    if (entry.isIntersecting) {
                        links.forEach(l => l.classList.remove('active'));
                        link.classList.add('active');
                    }
                });
            }, { rootMargin: '-20% 0px -70% 0px' });

            sections.forEach(s => observer.observe(s));
        </script>



    </section>

@endsection