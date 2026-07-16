@extends('layouts.base')
@section('title', 'A propos')

@section('content')
<section>
    <head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Devenir artisan sur Samaritain</title>
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
          orange: {
            DEFAULT: '#E8630A',
            dark: '#B84D08',
            light: '#FCE7D6',
          },
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
  .stamp {
    transform: rotate(-6deg);
  }
  @media (prefers-reduced-motion: no-preference) {
    .fade-up {
      animation: fadeUp 0.7s ease-out both;
    }
  }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .step-line {
    background-image: repeating-linear-gradient(to bottom, #1A1613 0, #1A1613 6px, transparent 6px, transparent 12px);
  }
</style>
</head>
<body class="bg-paper text-ink font-body antialiased">

  <!-- HERO -->
  <section class="blueprint-bg border-b border-ink/10">
    <div class="max-w-6xl mx-auto px-6 py-20 md:py-28 grid md:grid-cols-[1.2fr_0.8fr] gap-12 items-center">
      <div class="fade-up">
        <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.2em] uppercase text-orange-dark mb-6">
          <span class="w-6 h-px bg-orange-dark"></span>
          Pour les artisans de Brazzaville
        </p>
        <h1 class="font-display font-black text-5xl md:text-6xl leading-[1.05] tracking-tight">
          Trouvez plus de<br>
          <span class="text-orange">clients</span>, sans<br>
          courir après.
        </h1>
        <p class="mt-6 text-lg text-ink/70 max-w-md leading-relaxed">
          Plombiers, électriciens, maçons, peintres, menuisiers, climaticiens&nbsp;: votre profil est visible par les particuliers qui cherchent près de chez eux — et les demandes vous arrivent directement.
        </p>
        <div class="mt-8 flex flex-wrap items-center gap-4">
          <a href="#inscription" class="inline-flex items-center px-7 py-3.5 rounded-sm bg-orange text-paper font-display font-bold hover:bg-orange-dark transition-colors">
            Devenir artisan →
          </a>
          <a href="#comment-ca-marche" class="text-sm font-semibold text-ink/70 hover:text-ink underline underline-offset-4">
            Voir comment ça marche
          </a>
        </div>
      </div>

      <!-- signature stamp element -->
      <div class="flex justify-center md:justify-end">
        <div class="stamp w-52 h-52 md:w-60 md:h-60 rounded-full border-4 border-ink flex flex-col items-center justify-center text-center bg-paper shadow-[6px_6px_0_0_#E8630A] shrink-0">
          <span class="text-xs font-bold tracking-[0.15em] uppercase text-ink/60">Offre de lancement</span>
          <span class="font-display font-black text-4xl text-orange leading-none mt-2">6 mois</span>
          <span class="font-display font-bold text-sm uppercase tracking-wide mt-1">gratuits</span>
          <span class="text-[11px] text-ink/50 mt-2 px-4">0 commission · 0 abonnement</span>
        </div>
      </div>
    </div>
  </section>

  <!-- BENEFICES -->
  <section class="max-w-6xl mx-auto px-6 py-20">
    <h2 class="font-display font-extrabold text-3xl md:text-4xl tracking-tight max-w-lg">
      Ce que vous gagnez à rejoindre la plateforme
    </h2>
    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-px bg-ink/10 border border-ink/10">
      <div class="bg-paper p-7">
        <div class="w-10 h-10 rounded-sm bg-orange-light flex items-center justify-center text-orange font-display font-bold">↗</div>
        <h3 class="font-display font-bold text-lg mt-5">Plus de visibilité</h3>
        <p class="mt-2 text-sm text-ink/60 leading-relaxed">Votre profil est consultable par tous les particuliers qui cherchent votre métier dans votre zone.</p>
      </div>
      <div class="bg-paper p-7">
        <div class="w-10 h-10 rounded-sm bg-orange-light flex items-center justify-center text-orange font-display font-bold">★</div>
        <h3 class="font-display font-bold text-lg mt-5">Une réputation qui se construit</h3>
        <p class="mt-2 text-sm text-ink/60 leading-relaxed">Les avis de vos clients précédents renforcent votre crédibilité au fil du temps.</p>
      </div>
      <div class="bg-paper p-7">
        <div class="w-10 h-10 rounded-sm bg-orange-light flex items-center justify-center text-orange font-display font-bold">⏱</div>
        <h3 class="font-display font-bold text-lg mt-5">Moins de temps perdu</h3>
        <p class="mt-2 text-sm text-ink/60 leading-relaxed">Les demandes vous arrivent directement. Vous n'avez qu'à répondre.</p>
      </div>
      <div class="bg-paper p-7">
        <div class="w-10 h-10 rounded-sm bg-orange-light flex items-center justify-center text-orange font-display font-bold">▣</div>
        <h3 class="font-display font-bold text-lg mt-5">Un profil clé en main</h3>
        <p class="mt-2 text-sm text-ink/60 leading-relaxed">Photos de vos réalisations, description, zone d'intervention, disponibilités.</p>
      </div>
    </div>
  </section>

  <!-- CE DONT VOUS AVEZ BESOIN -->
  <section class="bg-ink text-paper">
    <div class="max-w-6xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-12">
      <div>
        <p class="text-xs font-bold tracking-[0.2em] uppercase text-orange mb-4">Avant de vous inscrire</p>
        <h2 class="font-display font-extrabold text-3xl tracking-tight">Ce dont vous avez besoin</h2>
        <p class="mt-4 text-paper/60 leading-relaxed max-w-sm">
          Aucun document administratif n'est exigé pour démarrer. L'inscription est pensée pour être simple, même sans statut d'entreprise formalisé.
        </p>
      </div>
      <ul class="space-y-4">
        <li class="flex gap-4 items-start border-b border-paper/10 pb-4">
          <span class="text-orange font-display font-bold">✓</span>
          <span class="text-paper/85">Votre nom complet et un numéro de téléphone actif, pour la vérification par SMS</span>
        </li>
        <li class="flex gap-4 items-start border-b border-paper/10 pb-4">
          <span class="text-orange font-display font-bold">✓</span>
          <span class="text-paper/85">Votre ou vos métier(s) / spécialité(s)</span>
        </li>
        <li class="flex gap-4 items-start border-b border-paper/10 pb-4">
          <span class="text-orange font-display font-bold">✓</span>
          <span class="text-paper/85">Votre ou vos zone(s) d'intervention (quartiers ou arrondissements)</span>
        </li>
        <li class="flex gap-4 items-start border-b border-paper/10 pb-4">
          <span class="text-orange font-display font-bold">✓</span>
          <span class="text-paper/85">Quelques photos de vos réalisations, si vous en avez</span>
        </li>
        <li class="flex gap-4 items-start pb-4">
          <span class="text-orange font-display font-bold">✓</span>
          <span class="text-paper/85">Une courte description de votre expérience et de vos services</span>
        </li>
      </ul>
    </div>
  </section>

  <!-- COMMENT CA MARCHE -->
  <section id="comment-ca-marche" class="max-w-6xl mx-auto px-6 py-20">
    <h2 class="font-display font-extrabold text-3xl md:text-4xl tracking-tight">Comment ça marche, concrètement</h2>
    <div class="mt-12 relative pl-10">
      <div class="absolute left-[11px] top-2 bottom-2 w-px step-line"></div>

      <div class="relative pb-10">
        <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-orange text-paper text-xs font-display font-bold flex items-center justify-center">1</div>
        <h3 class="font-display font-bold text-lg">Vous vous inscrivez</h3>
        <p class="mt-1 text-ink/60 max-w-md">En quelques minutes, via le bouton « Devenir artisan ».</p>
      </div>
      <div class="relative pb-10">
        <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-orange text-paper text-xs font-display font-bold flex items-center justify-center">2</div>
        <h3 class="font-display font-bold text-lg">Vous complétez votre profil</h3>
        <p class="mt-1 text-ink/60 max-w-md">Métier, zone d'intervention, photos, description.</p>
      </div>
      <div class="relative pb-10">
        <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-orange text-paper text-xs font-display font-bold flex items-center justify-center">3</div>
        <h3 class="font-display font-bold text-lg">Votre profil devient visible</h3>
        <p class="mt-1 text-ink/60 max-w-md">Auprès des clients qui recherchent votre spécialité.</p>
      </div>
      <div class="relative pb-10">
        <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-orange text-paper text-xs font-display font-bold flex items-center justify-center">4</div>
        <h3 class="font-display font-bold text-lg">Vous recevez des demandes</h3>
        <p class="mt-1 text-ink/60 max-w-md">De particuliers intéressés, directement sur la plateforme.</p>
      </div>
      <div class="relative">
        <div class="absolute -left-10 top-0 w-6 h-6 rounded-full bg-orange text-paper text-xs font-display font-bold flex items-center justify-center">5</div>
        <h3 class="font-display font-bold text-lg">Vous échangez et concluez</h3>
        <p class="mt-1 text-ink/60 max-w-md">Les prestations, selon vos propres conditions : tarifs, délais, modalités.</p>
      </div>
    </div>
  </section>

  <!-- CE QU'ON ATTEND DE VOUS -->
  <section class="bg-orange-light/40 border-y border-ink/10">
    <div class="max-w-6xl mx-auto px-6 py-16">
      <h2 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight">Ce qu'on attend de vous</h2>
      <p class="mt-3 text-ink/60 max-w-lg">Samaritain met en avant les artisans sérieux et fiables. Pour que la plateforme reste utile à tous :</p>
      <div class="mt-8 grid sm:grid-cols-2 gap-x-8 gap-y-4 max-w-3xl">
        <p class="flex gap-3 text-ink/80"><span class="text-orange-dark">—</span> Répondez aux demandes dans un délai raisonnable</p>
        <p class="flex gap-3 text-ink/80"><span class="text-orange-dark">—</span> Honorez les rendez-vous et engagements pris</p>
        <p class="flex gap-3 text-ink/80"><span class="text-orange-dark">—</span> Assurez un travail conforme à ce qui a été convenu</p>
        <p class="flex gap-3 text-ink/80"><span class="text-orange-dark">—</span> Restez courtois, même en cas de désaccord</p>
      </div>
      <p class="mt-8 text-sm text-ink/50 italic">Les avis clients étant visibles sur votre profil, votre sérieux est votre meilleur argument commercial sur la durée.</p>
    </div>
  </section>

  <!-- FAQ -->
  <section class="max-w-6xl mx-auto px-6 py-20">
    <h2 class="font-display font-extrabold text-3xl md:text-4xl tracking-tight mb-12">Questions fréquentes</h2>
    <div class="divide-y divide-ink/10 border-t border-b border-ink/10 max-w-3xl">
      <details class="group py-5">
        <summary class="flex items-center justify-between cursor-pointer font-display font-semibold list-none">
          Dois-je payer quelque chose maintenant ?
          <span class="text-orange text-xl group-open:rotate-45 transition-transform">+</span>
        </summary>
        <p class="mt-3 text-ink/60 leading-relaxed">Non. L'inscription et l'utilisation du profil sont gratuites pendant les 6 premiers mois après le lancement.</p>
      </details>
      <details class="group py-5">
        <summary class="flex items-center justify-between cursor-pointer font-display font-semibold list-none">
          Puis-je m'inscrire dans plusieurs métiers ?
          <span class="text-orange text-xl group-open:rotate-45 transition-transform">+</span>
        </summary>
        <p class="mt-3 text-ink/60 leading-relaxed">Oui. Si vous exercez plusieurs spécialités, vous pouvez toutes les indiquer sur votre profil.</p>
      </details>
      <details class="group py-5">
        <summary class="flex items-center justify-between cursor-pointer font-display font-semibold list-none">
          Que se passe-t-il si je n'ai pas de photos de mes travaux ?
          <span class="text-orange text-xl group-open:rotate-45 transition-transform">+</span>
        </summary>
        <p class="mt-3 text-ink/60 leading-relaxed">Ce n'est pas obligatoire pour démarrer, mais fortement recommandé : les profils avec photos inspirent davantage confiance aux clients.</p>
      </details>
      <details class="group py-5">
        <summary class="flex items-center justify-between cursor-pointer font-display font-semibold list-none">
          Puis-je modifier mon profil après l'inscription ?
          <span class="text-orange text-xl group-open:rotate-45 transition-transform">+</span>
        </summary>
        <p class="mt-3 text-ink/60 leading-relaxed">Oui, vous pouvez mettre à jour vos informations, zones d'intervention et photos à tout moment.</p>
      </details>
    </div>
  </section>

  <!-- CTA FINAL -->
  <section id="inscription" class="bg-ink text-paper">
    <div class="max-w-6xl mx-auto px-6 py-20 text-center">
      <h2 class="font-display font-black text-4xl md:text-5xl tracking-tight">Prêt à trouver plus de clients ?</h2>
      <p class="mt-4 text-paper/60 max-w-md mx-auto">Créez votre profil en quelques minutes. C'est gratuit pendant 6 mois.</p>
      <a href="{{ route('artisan.create') }}" class="mt-8 inline-flex items-center px-9 py-4 rounded-sm bg-orange text-paper font-display font-bold text-lg hover:bg-orange-dark transition-colors">
        Devenir artisan →
      </a>
    </div>
  </section>

</section>
@endsection