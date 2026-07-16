@extends('layouts.base')
@section('title', 'condition')

@section('content')
    <section>
        <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Conditions Générales d'Utilisation — Samaritain</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
      linear-gradient(rgba(26,22,19,0.05) 1px, transparent 1px),
      linear-gradient(90deg, rgba(26,22,19,0.05) 1px, transparent 1px);
    background-size: 28px 28px;
  }
  html { scroll-behavior: smooth; }
  .article-anchor { scroll-margin-top: 6.5rem; }
  .toc-link.active { color: #E8630A; font-weight: 700; }
  .toc-link.active::before { opacity: 1; }
  .toc-link::before {
    content: ''; position: absolute; left: -13px; top: 50%; transform: translateY(-50%);
    width: 6px; height: 6px; border-radius: 9999px; background: #E8630A; opacity: 0;
  }
</style>
</head>
<body class="bg-paper text-ink font-body antialiased">
  <section class="blueprint-bg border-b border-ink/10">
    <div class="max-w-7xl mx-auto px-6 py-16 md:py-20">
      <p class="italic text-ink/60 text-sm mb-3">Vivez sereinement.</p>
      <h1 class="font-display font-black text-4xl md:text-5xl tracking-tight">Conditions Générales d'Utilisation</h1>
      <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
        <span class="inline-flex items-center px-3 py-1.5 rounded-sm bg-ink text-paper font-semibold">Version 1.1</span>
        <span class="text-ink/50">Dernière mise à jour&nbsp;: 11 juillet 2026</span>
      </div>
      <div class="mt-8 max-w-2xl border-l-2 border-orange pl-5 py-1">
        <p class="text-sm text-ink/60 italic leading-relaxed">
          Document préparé à titre de base de travail. Il est fortement recommandé de le faire valider par un avocat ou juriste compétent en droit congolais et en droit OHADA avant toute mise en ligne ou usage contractuel opposable aux utilisateurs.
        </p>
      </div>
    </div>
  </section>

  <div class="max-w-7xl mx-auto px-6 py-14 grid lg:grid-cols-[240px_1fr] gap-14">

    <!-- TOC -->
    <nav aria-label="Sommaire" class="hidden lg:block">
      <div class="sticky top-24">
        <p class="text-xs font-bold tracking-[0.2em] uppercase text-ink/40 mb-4">Sommaire</p>
        <ul class="space-y-2.5 text-sm border-l border-ink/10 pl-5">
          <li><a href="#art1" class="toc-link relative text-ink/60 hover:text-ink transition-colors">1 — Objet</a></li>
          <li><a href="#art2" class="toc-link relative text-ink/60 hover:text-ink transition-colors">2 — Définitions</a></li>
          <li><a href="#art3" class="toc-link relative text-ink/60 hover:text-ink transition-colors">3 — Acceptation des CGU</a></li>
          <li><a href="#art4" class="toc-link relative text-ink/60 hover:text-ink transition-colors">4 — Accès &amp; compte</a></li>
          <li><a href="#art5" class="toc-link relative text-ink/60 hover:text-ink transition-colors">5 — Description des services</a></li>
          <li><a href="#art6" class="toc-link relative text-ink/60 hover:text-ink transition-colors">6 — Conditions financières</a></li>
          <li><a href="#art7" class="toc-link relative text-ink/60 hover:text-ink transition-colors">7 — Vérification &amp; fraude</a></li>
          <li><a href="#art8" class="toc-link relative text-ink/60 hover:text-ink transition-colors">8 — Obligations</a></li>
          <li><a href="#art9" class="toc-link relative text-ink/60 hover:text-ink transition-colors">9 — Signature électronique</a></li>
          <li><a href="#art10" class="toc-link relative text-ink/60 hover:text-ink transition-colors">10 — Propriété intellectuelle</a></li>
          <li><a href="#art11" class="toc-link relative text-ink/60 hover:text-ink transition-colors">11 — Données personnelles</a></li>
          <li><a href="#art12" class="toc-link relative text-ink/60 hover:text-ink transition-colors">12 — Responsabilité</a></li>
          <li><a href="#art13" class="toc-link relative text-ink/60 hover:text-ink transition-colors">13 — Suspension &amp; résiliation</a></li>
          <li><a href="#art14" class="toc-link relative text-ink/60 hover:text-ink transition-colors">14 — Réclamations</a></li>
          <li><a href="#art15" class="toc-link relative text-ink/60 hover:text-ink transition-colors">15 — Modification des CGU</a></li>
          <li><a href="#art16" class="toc-link relative text-ink/60 hover:text-ink transition-colors">16 — Droit applicable</a></li>
          <li><a href="#art17" class="toc-link relative text-ink/60 hover:text-ink transition-colors">17 — Contact</a></li>
        </ul>
      </div>
    </nav>

    <!-- ARTICLES -->
    <main class="min-w-0 space-y-16">

      <article id="art1" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">01</span> Objet
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>Les présentes Conditions Générales d'Utilisation (ci-après « CGU ») ont pour objet de définir les modalités et conditions dans lesquelles Samaritain met à disposition des utilisateurs sa plateforme numérique (ci-après la « Plateforme »), ainsi que les droits et obligations des parties dans ce cadre.</p>
          <p>La Plateforme regroupe plusieurs modules de services&nbsp;: la publication et la recherche de biens immobiliers à louer, la publication et la vente de terrains et parcelles, la mise en relation avec des artisans, la formation aéronautique, la signature électronique de documents, et la Tontine Digitale.</p>
          <p>Toute utilisation de la Plateforme implique l'acceptation pleine et entière des présentes CGU.</p>
        </div>
      </article>

      <article id="art2" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">02</span> Définitions
        </h2>
        <dl class="mt-5 divide-y divide-ink/10 border-t border-b border-ink/10 max-w-3xl">
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Plateforme »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Le site internet et/ou l'application mobile Samaritain, ainsi que l'ensemble des services qui y sont accessibles.</dd>
          </div>
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Utilisateur »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Toute personne physique ou morale qui accède à la Plateforme, qu'elle y crée un compte ou non.</dd>
          </div>
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Propriétaire »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Utilisateur publiant une annonce de location ou de vente d'un bien immobilier (logement, terrain, parcelle).</dd>
          </div>
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Artisan »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Utilisateur professionnel proposant ses services à travers le module de mise en relation artisanale.</dd>
          </div>
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Locataire » / « Acheteur »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Utilisateur consultant les annonces et cherchant à louer ou acheter un bien.</dd>
          </div>
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Agent Samaritain »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Représentant mandaté par Samaritain pour encadrer les visites de biens et effectuer les vérifications prévues aux présentes.</dd>
          </div>
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Pass visite »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Titre d'accès payant permettant à un Utilisateur de visiter un nombre défini de biens pendant une durée déterminée, dans les conditions fixées à l'Article 6.</dd>
          </div>
          <div class="grid sm:grid-cols-[10rem_1fr] gap-1.5 sm:gap-6 py-4">
            <dt class="font-display font-bold text-sm">« Contenu »</dt>
            <dd class="text-ink/70 text-sm leading-relaxed">Toute information, texte, image, document ou donnée publiée par un Utilisateur sur la Plateforme.</dd>
          </div>
        </dl>
      </article>

      <article id="art3" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">03</span> Acceptation des CGU
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>L'inscription sur la Plateforme et/ou l'utilisation de l'un quelconque de ses services vaut acceptation sans réserve des présentes CGU. Si l'Utilisateur n'accepte pas tout ou partie des présentes CGU, il doit s'abstenir d'utiliser la Plateforme.</p>
          <p>Samaritain se réserve le droit de faire évoluer les présentes CGU dans les conditions prévues à l'<a href="#art15" class="text-orange-dark font-semibold hover:underline">Article 15</a>.</p>
        </div>
      </article>

      <article id="art4" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">04</span> Accès à la plateforme et création de compte
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>L'accès à certaines fonctionnalités de la Plateforme nécessite la création d'un compte utilisateur. L'Utilisateur s'engage à fournir des informations exactes, complètes et à jour lors de son inscription, et à les maintenir à jour.</p>
          <p>La création de compte peut nécessiter une vérification par numéro de téléphone (code envoyé par SMS). L'Utilisateur est seul responsable de la confidentialité de ses identifiants et de toute activité réalisée depuis son compte.</p>
          <p>Samaritain se réserve le droit de refuser, suspendre ou supprimer tout compte en cas d'informations inexactes, frauduleuses, ou de non-respect des présentes CGU.</p>
        </div>
      </article>

      <article id="art5" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">05</span> Description des services
        </h2>

        <div class="mt-8 max-w-3xl">
          <h3 class="font-display font-bold text-lg">5.1 Module Immobilier — Location de biens</h3>
          <div class="mt-3 space-y-3 text-ink/75 leading-relaxed text-[15px]">
            <p>Ce module permet à un Propriétaire de publier une annonce de location, et à un Locataire potentiel de consulter les annonces, de contacter le Propriétaire et d'organiser une visite du bien.</p>
            <p><span class="font-semibold text-ink">Publication&nbsp;:</span> la publication d'une annonce de location est entièrement gratuite pour le Propriétaire.</p>
            <p><span class="font-semibold text-ink">Commission&nbsp;:</span> Samaritain ne perçoit aucune commission sur les locations conclues entre le Propriétaire et le Locataire. Le montant du loyer convenu revient intégralement au Propriétaire.</p>
            <p>Les visites de biens à louer sont conditionnées à l'achat d'un Pass visite par le Locataire potentiel, dans les conditions fixées à l'Article 6.4, et sont systématiquement encadrées par un Agent Samaritain.</p>
            <p>Samaritain n'intervient pas dans la conclusion du bail, qui demeure une affaire strictement privée entre le Propriétaire et le Locataire, celui-ci restant libre d'en fixer les conditions (durée, garanties, modalités de paiement).</p>
          </div>
        </div>

        <div class="mt-10 max-w-3xl">
          <h3 class="font-display font-bold text-lg">5.2 Module Immobilier — Vente de terrains et parcelles</h3>
          <div class="mt-3 space-y-3 text-ink/75 leading-relaxed text-[15px]">
            <p>Ce module permet à un Propriétaire de publier une annonce de vente de terrain ou de parcelle, et à un Acheteur potentiel de consulter les annonces, de contacter le Propriétaire et de visiter le bien.</p>
            <p><span class="font-semibold text-ink">Publication&nbsp;:</span> la publication d'une annonce de vente de terrain ou de parcelle est gratuite.</p>
            <p><span class="font-semibold text-ink">Commission&nbsp;:</span> Samaritain perçoit une commission sur toute vente conclue à la suite d'une mise en relation via la Plateforme, calculée selon le barème suivant&nbsp;:</p>
          </div>

          <div class="mt-4 overflow-hidden rounded-sm border border-ink/10">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-ink text-paper">
                  <th class="text-left font-display font-bold px-4 py-3">Prix du bien</th>
                  <th class="text-left font-display font-bold px-4 py-3">Commission Samaritain</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-ink/10">
                <tr class="odd:bg-orange-light/30">
                  <td class="px-4 py-3 text-ink/80">Inférieur ou égal à 10 000 000 FCFA</td>
                  <td class="px-4 py-3 font-display font-bold text-orange-dark">5%</td>
                </tr>
                <tr class="odd:bg-orange-light/30">
                  <td class="px-4 py-3 text-ink/80">De 11 000 000 à 40 000 000 FCFA</td>
                  <td class="px-4 py-3 font-display font-bold text-orange-dark">4%</td>
                </tr>
                <tr class="odd:bg-orange-light/30">
                  <td class="px-4 py-3 text-ink/80">Supérieur à 40 000 000 FCFA</td>
                  <td class="px-4 py-3 font-display font-bold text-orange-dark">3%</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-4 space-y-3 text-ink/75 leading-relaxed text-[15px]">
            <p>La commission n'est due qu'au moment où la vente est effectivement conclue entre les parties. Elle est calculée sur le prix de vente final du bien.</p>
            <p>La première visite d'un Acheteur potentiel sur un bien donné est gratuite et encadrée par un Agent Samaritain. À compter de la deuxième visite, l'Acheteur doit se munir d'un Pass visite dans les conditions fixées à l'Article 6.4.</p>
            <p>La publication d'une annonce de vente de terrain ou de parcelle est conditionnée à la fourniture, par le Propriétaire, du titre de propriété du bien et d'une pièce d'identité valide, conformément à l'Article 7.</p>
          </div>
        </div>

        <div class="mt-10 max-w-3xl">
          <h3 class="font-display font-bold text-lg">5.3 Module Artisanat</h3>
          <div class="mt-3 space-y-3 text-ink/75 leading-relaxed text-[15px]">
            <p>Ce module permet à un Artisan de créer un profil professionnel visible par les Utilisateurs recherchant un service dans son domaine, et d'être mis en relation avec des clients potentiels.</p>
            <p><span class="font-semibold text-ink">Gratuité de lancement&nbsp;:</span> les profils Artisan créés sont gratuits pendant une durée de six (6) mois suivant le lancement de la Plateforme. Aucun frais ni commission n'est prélevé durant cette période.</p>
            <p><span class="font-semibold text-ink">Abonnement trimestriel&nbsp;:</span> à l'issue de cette période de gratuité, l'accès et le maintien du profil Artisan sont soumis à un abonnement trimestriel, dont le montant sera communiqué aux Artisans inscrits avant son entrée en vigueur.</p>
            <p>Samaritain n'intervient pas dans la conclusion des prestations entre l'Artisan et son client, qui demeurent une affaire strictement privée (tarifs, délais, modalités d'exécution).</p>
          </div>
        </div>

        <div class="mt-10 max-w-3xl">
          <h3 class="font-display font-bold text-lg">5.4 Module Signature électronique</h3>
          <div class="mt-3 space-y-3 text-ink/75 leading-relaxed text-[15px]">
            <p>Ce module permet aux Utilisateurs de signer électroniquement des documents dans le cadre de leurs opérations sur la Plateforme (baux, contrats, actes divers), avec verrouillage cryptographique du document signé et vérification par code envoyé par SMS.</p>
            <p>La signature électronique réalisée via ce module a vocation à produire les effets juridiques d'une signature manuscrite, dans les conditions prévues par les règles applicables en matière de preuve électronique dans l'espace OHADA, sous réserve de la nature du document concerné et du respect des conditions de validité applicables.</p>
          </div>
        </div>
      </article>

      <article id="art6" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">06</span> Conditions financières
        </h2>

        <div class="mt-6 grid sm:grid-cols-2 gap-px bg-ink/10 border border-ink/10 max-w-3xl">
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">6.1 Récapitulatif — Artisans</h3>
            <ul class="mt-3 space-y-2 text-sm text-ink/75">
              <li class="flex gap-2"><span class="text-orange">—</span> Profil gratuit pendant 6 mois à compter du lancement de la Plateforme.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Abonnement trimestriel applicable ensuite, montant communiqué à l'avance.</li>
            </ul>
          </div>
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">6.2 Récapitulatif — Location de biens</h3>
            <ul class="mt-3 space-y-2 text-sm text-ink/75">
              <li class="flex gap-2"><span class="text-orange">—</span> Publication d'annonce gratuite.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Aucune commission sur la location conclue.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Pass visite à la charge du Locataire potentiel (cf. Article 6.4).</li>
            </ul>
          </div>
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">6.3 Récapitulatif — Vente de terrains et parcelles</h3>
            <ul class="mt-3 space-y-2 text-sm text-ink/75">
              <li class="flex gap-2"><span class="text-orange">—</span> Publication d'annonce gratuite.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Commission due uniquement en cas de vente conclue, selon le barème de l'Article 5.2.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Première visite gratuite ; Pass visite exigé à partir de la deuxième visite (cf. Article 6.4).</li>
            </ul>
          </div>
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">6.4 Pass visite</h3>
            <p class="mt-3 text-sm text-ink/75 leading-relaxed">
              <span class="font-display font-black text-orange text-lg">5 000 FCFA</span> — valable 1 semaine, jusqu'à 3 biens.
            </p>
          </div>
        </div>

        <div class="mt-6 space-y-3 text-ink/75 leading-relaxed max-w-3xl text-[15px]">
          <p>Le Pass visite est un titre d'accès payant, d'un montant de 5 000 FCFA, valable pendant une durée d'une (1) semaine à compter de son achat, et permettant à son titulaire de visiter jusqu'à trois (3) biens différents publiés sur la Plateforme.</p>
          <p>Le Pass visite est à la charge de l'Utilisateur souhaitant visiter un bien (Locataire ou Acheteur potentiel) et non du Propriétaire. Chaque visite réalisée dans le cadre d'un Pass visite est encadrée par un Agent Samaritain.</p>
          <p>Le Pass visite n'est pas exigé pour la première visite d'un bien à vendre (terrain ou parcelle) par un même Acheteur, conformément à l'Article 5.2. Il est en revanche systématiquement exigé pour la visite d'un bien à louer, dès la première visite, conformément à l'Article 5.1.</p>
          <p>Le Pass visite ne garantit pas la conclusion d'une location ou d'une vente et ne fait l'objet d'aucun remboursement, sauf en cas d'annulation imputable à Samaritain.</p>
        </div>
      </article>

      <article id="art7" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">07</span> Vérification d'identité et lutte contre la fraude
        </h2>
        <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">Samaritain attache une importance particulière à la sécurité des transactions réalisées via la Plateforme et met en œuvre des mesures de vérification destinées à limiter les risques de fraude, notamment immobilière et foncière.</p>

        <div class="mt-8 max-w-3xl">
          <h3 class="font-display font-bold text-lg">7.1 Location de biens</h3>
          <p class="mt-3 text-ink/75 leading-relaxed text-[15px]">Une preuve de propriété (titre foncier, contrat, facture au nom du Propriétaire, ou document équivalent) est demandée lors de la publication d'une annonce de location. À défaut de document disponible, Samaritain peut procéder à une vérification sur place afin de confirmer la qualité de propriétaire de l'Utilisateur.</p>
        </div>

        <div class="mt-8 max-w-3xl">
          <h3 class="font-display font-bold text-lg">7.2 Vente de terrains et parcelles</h3>
          <div class="mt-3 space-y-3 text-ink/75 leading-relaxed text-[15px]">
            <p>La publication d'une annonce de vente de terrain ou de parcelle est subordonnée à la fourniture obligatoire, par le Propriétaire, de son titre de propriété et d'une pièce d'identité valide. Cette exigence est impérative&nbsp;: aucune annonce ne peut être publiée sans ces documents.</p>
            <p>Les Utilisateurs disposant uniquement d'un certificat d'achat — situation courante en République du Congo — ne sont pas empêchés de publier une annonce à ce seul motif. Samaritain met à leur disposition, via ses partenaires notaires agréés, un accompagnement pour la régularisation de leur situation et l'obtention d'un titre de propriété en bonne et due forme. L'utilisateur concerné est invité à contacter Samaritain pour plus d'informations sur cette démarche.</p>
          </div>
        </div>

        <div class="mt-8 max-w-3xl">
          <h3 class="font-display font-bold text-lg">7.3 Portée de la vérification</h3>
          <p class="mt-3 text-ink/75 leading-relaxed text-[15px]">Les vérifications réalisées par Samaritain visent à réduire le risque de fraude mais ne constituent pas une garantie absolue de l'exactitude des informations fournies par les Utilisateurs, ni une expertise juridique du titre présenté. Samaritain ne saurait être tenue responsable en cas de fraude commise par un Utilisateur en dépit des vérifications mises en œuvre, sans préjudice des recours dont disposerait la victime à l'encontre de son auteur.</p>
        </div>

        <div class="mt-8 max-w-3xl">
          <h3 class="font-display font-bold text-lg">7.4 Biens n'ayant pas fait l'objet d'une vérification complète</h3>
          <div class="mt-3 space-y-3 text-ink/75 leading-relaxed text-[15px]">
            <p>En raison de contraintes opérationnelles, Samaritain ne peut garantir que l'ensemble des annonces de vente de terrains et parcelles publiées sur la Plateforme ait fait l'objet de la vérification complète du titre de propriété et de la pièce d'identité du propriétaire prévue à l'Article 7.2. Certains biens peuvent ainsi être publiés sans que cette vérification ait pu être intégralement réalisée par Samaritain.</p>
            <p>Lorsqu'un bien n'a pas fait l'objet d'une telle vérification, l'acheteur potentiel en est informé avant toute visite et doit expressément confirmer vouloir procéder à la visite en connaissance de cause.</p>
            <p>Samaritain recommande vivement à tout Acheteur de consulter un notaire ou tout autre professionnel du droit compétent avant toute intention d'achat portant sur un terrain ou une parcelle, et en tout état de cause avant la signature de tout acte ou le versement de quelque somme que ce soit, que le bien concerné ait ou non fait l'objet d'une vérification par Samaritain.</p>
            <p>Samaritain ne saurait être tenue responsable des conséquences résultant de l'absence de vérification d'un bien, ni de l'inexactitude ou de la fausseté des informations, documents ou titres fournis par un Propriétaire, sans préjudice des recours dont disposerait l'Acheteur à l'encontre du Propriétaire ou de tout tiers responsable. Cette limitation de responsabilité ne s'applique pas en cas de faute prouvée de Samaritain dans la conduite des vérifications qu'elle a elle-même engagées.</p>
          </div>
        </div>
      </article>

      <article id="art8" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">08</span> Obligations des utilisateurs
        </h2>

        <div class="mt-6 grid sm:grid-cols-2 gap-px bg-ink/10 border border-ink/10 max-w-3xl">
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">8.1 Obligations communes</h3>
            <ul class="mt-3 space-y-2 text-sm text-ink/75">
              <li class="flex gap-2"><span class="text-orange">—</span> Fournir des informations exactes, complètes et à jour.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Utiliser la Plateforme conformément à sa destination et aux présentes CGU.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Ne pas publier de Contenu trompeur, mensonger, illicite ou portant atteinte aux droits de tiers.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Respecter les autres utilisateurs et adopter un comportement courtois dans les échanges.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Ne pas détourner la Plateforme pour contourner les mécanismes prévus (Pass visite, commissions).</li>
            </ul>
          </div>
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">8.2 Obligations des Propriétaires</h3>
            <ul class="mt-3 space-y-2 text-sm text-ink/75">
              <li class="flex gap-2"><span class="text-orange">—</span> Publier des informations exactes sur le bien (prix, état, disponibilité réelle).</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Mettre à jour ou retirer l'annonce dès que le bien est loué ou vendu.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Fournir les documents de vérification requis (Article 7) et coopérer aux vérifications sur place le cas échéant.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Répondre aux demandes dans un délai raisonnable.</li>
            </ul>
          </div>
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">8.3 Obligations des Artisans</h3>
            <ul class="mt-3 space-y-2 text-sm text-ink/75">
              <li class="flex gap-2"><span class="text-orange">—</span> Répondre aux demandes dans un délai raisonnable et honorer les rendez-vous pris.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Assurer un travail conforme à ce qui a été convenu avec le client.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Maintenir à jour les informations de son profil (métier, zone d'intervention, disponibilités).</li>
            </ul>
          </div>
          <div class="bg-paper p-6">
            <h3 class="font-display font-bold text-sm uppercase tracking-wide text-orange-dark">8.4 Obligations des Locataires et Acheteurs</h3>
            <ul class="mt-3 space-y-2 text-sm text-ink/75">
              <li class="flex gap-2"><span class="text-orange">—</span> Se munir d'un Pass visite valide lorsque celui-ci est requis avant toute visite.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Se comporter de façon respectueuse envers le Propriétaire, l'Artisan et l'Agent Samaritain lors des visites ou échanges.</li>
              <li class="flex gap-2"><span class="text-orange">—</span> Ne pas contourner la Plateforme pour éviter les frais applicables après mise en relation via Samaritain.</li>
            </ul>
          </div>
        </div>
      </article>

      <article id="art9" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">09</span> Signature électronique et valeur juridique
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>Lorsqu'un Utilisateur recourt au module de signature électronique de la Plateforme, il reconnaît que le procédé technique utilisé (verrouillage cryptographique du document et vérification par code à usage unique envoyé par SMS) a pour objet de garantir l'identification du signataire et l'intégrité du document signé.</p>
          <p>Il appartient à l'Utilisateur de s'assurer que le document concerné se prête à une signature électronique au regard de sa nature et des règles qui lui sont applicables. Samaritain ne saurait garantir la validité juridique d'un document dans des situations où une forme particulière serait exigée par la loi.</p>
        </div>
      </article>

      <article id="art10" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">10</span> Propriété intellectuelle
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>La Plateforme, sa structure, son design, ses logos, sa marque « Samaritain » et l'ensemble des éléments qui la composent sont protégés au titre de la propriété intellectuelle et demeurent la propriété exclusive de Samaritain ou de ses partenaires.</p>
          <p>Toute reproduction, représentation ou exploitation, totale ou partielle, sans autorisation préalable, est interdite. Les Utilisateurs conservent la propriété du Contenu qu'ils publient (photos, descriptions), mais concèdent à Samaritain une licence non exclusive d'utilisation de ce Contenu aux seules fins de fonctionnement et de promotion de la Plateforme.</p>
        </div>
      </article>

      <article id="art11" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">11</span> Protection des données personnelles
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>Samaritain collecte et traite des données à caractère personnel (identité, coordonnées, documents de vérification, données de localisation des biens) nécessaires au fonctionnement des services décrits aux présentes.</p>
          <p>Ces données sont traitées de façon confidentielle et ne sont communiquées à des tiers que dans la mesure nécessaire à la réalisation des services (par exemple, mise en relation avec un notaire partenaire dans le cadre d'une régularisation de titre) ou en exécution d'une obligation légale.</p>
          <p>Les modalités précises de collecte, de conservation et d'exercice des droits des Utilisateurs sur leurs données personnelles sont détaillées dans une Politique de confidentialité distincte, qui complète les présentes CGU.</p>
        </div>
      </article>

      <article id="art12" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">12</span> Responsabilité
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>Samaritain agit en qualité d'intermédiaire technique de mise en relation entre Utilisateurs. Elle n'est ni propriétaire, ni locataire, ni acheteur, ni artisan, et n'est pas partie aux contrats conclus entre Utilisateurs (baux, actes de vente, contrats de prestation).</p>
          <p>En conséquence, Samaritain ne saurait être tenue responsable de l'inexécution, de la mauvaise exécution ou des conséquences dommageables d'un contrat conclu entre Utilisateurs à la suite d'une mise en relation via la Plateforme, sous réserve des obligations de vérification qui lui incombent expressément au titre des présentes.</p>
          <p>Samaritain s'efforce d'assurer un accès continu à la Plateforme mais ne garantit pas l'absence d'interruption, notamment pour des raisons de maintenance ou de cas de force majeure.</p>
        </div>
      </article>

      <article id="art13" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">13</span> Suspension et résiliation
        </h2>
        <div class="mt-5 space-y-4 text-ink/75 leading-relaxed max-w-3xl">
          <p>Samaritain peut suspendre ou résilier, à tout moment et sans préavis en cas de manquement grave, l'accès d'un Utilisateur à la Plateforme, notamment en cas de fourniture de documents falsifiés, de comportement frauduleux, ou de violation répétée des présentes CGU.</p>
          <p>Tout Utilisateur peut demander la clôture de son compte à tout moment, sans préjudice des sommes éventuellement dues à Samaritain au titre de commissions ou d'abonnements en cours.</p>
        </div>
      </article>

      <article id="art14" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">14</span> Réclamations et médiation
        </h2>
        <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">Toute réclamation relative à l'utilisation de la Plateforme peut être adressée à Samaritain par les canaux de contact indiqués à l'Article 17. Samaritain s'efforce d'apporter une réponse dans un délai raisonnable.</p>
      </article>

      <article id="art15" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">15</span> Modification des CGU
        </h2>
        <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">Samaritain se réserve le droit de modifier les présentes CGU à tout moment, notamment pour tenir compte d'évolutions légales, techniques ou commerciales (par exemple, la fixation du montant de l'abonnement trimestriel Artisan). Les Utilisateurs seront informés de toute modification substantielle avant son entrée en vigueur. La poursuite de l'utilisation de la Plateforme après notification vaut acceptation des CGU modifiées.</p>
      </article>

      <article id="art16" class="article-anchor">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">16</span> Droit applicable et juridiction
        </h2>
        <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">Les présentes CGU sont soumises au droit de la République du Congo et aux dispositions applicables de l'Organisation pour l'Harmonisation en Afrique du Droit des Affaires (OHADA). Tout litige relatif à leur validité, leur interprétation ou leur exécution relève, à défaut de résolution amiable, de la compétence des juridictions congolaises.</p>
      </article>

      <article id="art17" class="article-anchor pb-4">
        <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight flex items-baseline gap-3">
          <span class="text-orange">17</span> Contact
        </h2>
        <p class="mt-5 text-ink/75 leading-relaxed max-w-3xl">Pour toute question relative aux présentes CGU, à la régularisation d'un titre de propriété, ou à tout autre sujet lié à l'utilisation de la Plateforme, l'Utilisateur peut contacter Samaritain via les canaux de contact indiqués sur la Plateforme.</p>
      </article>

      <div class="pt-10 border-t border-ink/10 text-center">
        <p class="italic text-ink/50 text-sm">Fin des Conditions Générales d'Utilisation — Samaritain, « Vivez sereinement. »</p>
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