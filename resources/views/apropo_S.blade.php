@extends('layouts.base')
@section('title', 'A propos')


@section('content')
<section>
  <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>À propos — Samaritain</title>
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
  .stamp { transform: rotate(-4deg); }
  @media (prefers-reduced-motion: no-preference) {
    .fade-up { animation: fadeUp 0.7s ease-out both; }
  }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
</head>
<body class="bg-paper text-ink font-body antialiased">

  <!-- HERO -->
  <section class="blueprint-bg border-b border-ink/10">
    <div class="max-w-6xl mx-auto px-6 py-20 md:py-28">
      <p class="fade-up inline-flex items-center gap-2 text-xs font-bold tracking-[0.2em] uppercase text-orange-dark mb-6">
        <span class="w-6 h-px bg-orange-dark"></span>
        À propos de Samaritain
      </p>
      <h1 class="fade-up font-display font-black text-5xl md:text-6xl leading-[1.05] tracking-tight max-w-3xl">
        Vivez <span class="text-orange">sereinement</span>.
      </h1>
      <p class="fade-up mt-8 text-lg text-ink/70 max-w-2xl leading-relaxed">
        Samaritain est né d'un constat simple&nbsp;: à Brazzaville et plus largement dans la zone CEMAC, trouver un logement fiable, faire appel à un artisan de confiance, sécuriser un document important ou épargner collectivement restent des démarches semées d'incertitude, d'intermédiaires opaques et de risques évitables.
      </p>
      <p class="mt-4 text-lg text-ink/70 max-w-2xl leading-relaxed">
        Nous avons voulu construire une plateforme qui change cela — une seule adresse numérique pour les besoins essentiels du quotidien, pensée pour et avec le marché congolais.
      </p>
      <p class="mt-6 max-w-2xl text-ink/60 leading-relaxed">
        Notre nom porte notre raison d'être&nbsp;: être présent, utile et digne de confiance dans les moments qui comptent.
      </p>
    </div>
  </section>

  <!-- CALLOUT COMMISSION -->
  <section class="max-w-6xl mx-auto px-6 -mt-10 md:-mt-1 relative z-10">
    <div class="bg-ink text-paper rounded-sm p-8 md:p-10 shadow-[8px_8px_0_0_#E8630A] flex flex-col md:flex-row gap-6 md:items-center">
      <div class="stamp shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-full border-2 border-orange flex items-center justify-center text-3xl md:text-4xl">
        🏠
      </div>
      <div>
        <p class="font-display font-black text-xl md:text-2xl tracking-tight text-orange uppercase">Sans frais de commission pour les propriétaires</p>
        <p class="mt-2 text-paper/70 leading-relaxed max-w-2xl">
          Chez Samaritain, louer votre bien ne vous coûte rien. Aucune commission n'est prélevée sur les propriétaires — notre modèle repose sur un principe simple&nbsp;: ce sont ceux qui cherchent un logement qui contribuent, pas ceux qui le proposent.
        </p>
      </div>
    </div>
  </section>

  <!-- VISION -->
  <section class="max-w-6xl mx-auto px-6 py-20 md:py-24 grid md:grid-cols-[1fr_1.4fr] gap-12">
    <div>
      <p class="text-xs font-bold tracking-[0.2em] uppercase text-orange-dark mb-4">Notre vision</p>
      <h2 class="font-display font-extrabold text-3xl tracking-tight">Un marché plus transparent profite à tout le monde</h2>
    </div>
    <div class="space-y-4 text-ink/70 leading-relaxed">
      <p>Aux familles qui cherchent un toit, aux propriétaires qui veulent louer sans complications, aux artisans qui méritent une visibilité juste, et à une économie locale qui a besoin d'outils numériques adaptés à ses réalités plutôt qu'importés tels quels d'ailleurs.</p>
      <p>Samaritain veut devenir le réflexe naturel de tout Congolais confronté à une question du quotidien&nbsp;: <span class="italic text-ink">où louer, qui appeler, comment signer, comment épargner ?</span> Une plateforme unique, vérifiée, et pensée localement — plutôt qu'une multiplication de solutions fragmentées, informelles ou importées.</p>
    </div>
  </section>

  <!-- CE QUE NOUS CONSTRUISONS -->
  <section class="border-y border-ink/10 bg-orange-light/20">
    <div class="max-w-6xl mx-auto px-6 py-20">
      <p class="text-xs font-bold tracking-[0.2em] uppercase text-orange-dark mb-4">Ce que nous construisons</p>
      <h2 class="font-display font-extrabold text-3xl md:text-4xl tracking-tight max-w-xl">Une plateforme multi-services, un principe commun</h2>
      <p class="mt-4 max-w-xl text-ink/60">La vérification et la confiance avant tout.</p>

      <div class="mt-12 grid md:grid-cols-2 gap-px bg-ink/10 border border-ink/10">
        <div class="bg-paper p-8">
          <div class="w-10 h-10 rounded-sm bg-orange text-paper flex items-center justify-center font-display font-bold">🏠</div>
          <h3 class="font-display font-bold text-lg mt-5">Immobilier</h3>
          <p class="text-xs font-bold uppercase tracking-wide text-orange-dark mt-1">Sans frais de commission pour les propriétaires</p>
          <p class="mt-3 text-sm text-ink/65 leading-relaxed">Des annonces de location et de vente vérifiées, avec un accompagnement humain — visites encadrées, agents de terrain, et un parcours pensé pour protéger autant les propriétaires que les locataires ou acheteurs. Publier et louer votre bien via Samaritain ne coûte rien&nbsp;: aucune commission n'est jamais prélevée sur les propriétaires bailleurs.</p>
        </div>
        <div class="bg-paper p-8">
          <div class="w-10 h-10 rounded-sm bg-orange text-paper flex items-center justify-center font-display font-bold">🔧</div>
          <h3 class="font-display font-bold text-lg mt-5">Services & artisanat</h3>
          <p class="mt-3 text-sm text-ink/65 leading-relaxed">Une marketplace pour trouver rapidement des artisans qualifiés — plombiers, électriciens, maçons et bien d'autres — avec des profils qui inspirent confiance dès le premier contact.</p>
        </div>
        <div class="bg-paper p-8">
          <div class="w-10 h-10 rounded-sm bg-orange text-paper flex items-center justify-center font-display font-bold">✒</div>
          <h3 class="font-display font-bold text-lg mt-5">Signature électronique</h3>
          <p class="mt-3 text-sm text-ink/65 leading-relaxed">Un module conforme aux exigences de l'espace OHADA pour signer, verrouiller et archiver des documents importants sans déplacement ni papier, avec une valeur juridique reconnue.</p>
        </div>
        <div class="bg-paper p-8">
          <div class="w-10 h-10 rounded-sm bg-ink text-paper flex items-center justify-center font-display font-bold">＋</div>
          <h3 class="font-display font-bold text-lg mt-5">Et demain</h3>
          <p class="mt-3 text-sm text-ink/65 leading-relaxed">D'autres briques viendront enrichir l'écosystème Samaritain à mesure que la confiance et l'usage s'installent — toujours guidées par les besoins réels du marché plutôt que par la technologie pour elle-même.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- NOTRE APPROCHE -->
  <section class="max-w-6xl mx-auto px-6 py-20 md:py-24">
    <p class="text-xs font-bold tracking-[0.2em] uppercase text-orange-dark mb-4">Notre approche</p>
    <h2 class="font-display font-extrabold text-3xl md:text-4xl tracking-tight max-w-xl">Quatre principes qui guident chaque décision</h2>

    <div class="mt-12 grid sm:grid-cols-2 gap-x-10 gap-y-10">
      <div class="flex gap-5">
        <span class="font-display font-black text-3xl text-orange/30 shrink-0">01</span>
        <div>
          <h3 class="font-display font-bold text-lg">La vérification comme fondation</h3>
          <p class="mt-2 text-sm text-ink/65 leading-relaxed">Chaque annonce, chaque profil, chaque document passe par un contrôle pensé pour éliminer les mauvaises surprises — pas pour ralentir, mais pour rassurer.</p>
        </div>
      </div>
      <div class="flex gap-5">
        <span class="font-display font-black text-3xl text-orange/30 shrink-0">02</span>
        <div>
          <h3 class="font-display font-bold text-lg">Le local avant tout</h3>
          <p class="mt-2 text-sm text-ink/65 leading-relaxed">Nos choix techniques, juridiques et commerciaux sont pensés pour le Congo-Brazzaville&nbsp;: hébergement local, conformité à la loi n°29-2019 sur la protection des données, tarification adaptée au pouvoir d'achat local.</p>
        </div>
      </div>
      <div class="flex gap-5">
        <span class="font-display font-black text-3xl text-orange/30 shrink-0">03</span>
        <div>
          <h3 class="font-display font-bold text-lg">La simplicité qui inspire confiance</h3>
          <p class="mt-2 text-sm text-ink/65 leading-relaxed">La technologie doit s'effacer derrière l'usage. Pas de jargon, pas de complexité inutile — juste des parcours clairs pour des besoins concrets.</p>
        </div>
      </div>
      <div class="flex gap-5">
        <span class="font-display font-black text-3xl text-orange/30 shrink-0">04</span>
        <div>
          <h3 class="font-display font-bold text-lg">Une équipe de terrain</h3>
          <p class="mt-2 text-sm text-ink/65 leading-relaxed">Derrière chaque fonctionnalité, une équipe basée à Brazzaville, qui connaît les réalités du marché parce qu'elle les vit au quotidien.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ENGAGEMENT -->
  <section class="bg-ink text-paper">
    <div class="max-w-6xl mx-auto px-6 py-20 md:py-24 text-center">
      <p class="text-xs font-bold tracking-[0.2em] uppercase text-orange mb-6">Notre engagement</p>
      <p class="font-display font-bold text-2xl md:text-3xl leading-snug max-w-3xl mx-auto tracking-tight">
        Samaritain s'engage à opérer avec la même rigueur qu'un partenaire de confiance&nbsp;: transparence sur les frais, respect des données personnelles, accompagnement des propriétaires et artisans dans leur transition numérique, et une volonté constante de mettre le sérieux au service de la sérénité.
      </p>
      <div class="mt-12 flex items-center justify-center gap-3">
        <span class="w-8 h-px bg-orange"></span>
        <span class="font-display font-black text-xl tracking-tight">Samaritain — Vivez sereinement.</span>
        <span class="w-8 h-px bg-orange"></span>
      </div>
    </div>
  </section>
</section>
@endsection