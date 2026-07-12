@props([
    'properties' => null
])

<header class="bg-primary mt-2 mb-2 rounded-2xl overflow-hidden relative">

    {{-- Fond décoratif --}}
    <div class="absolute inset-0 opacity-10"
        style="background-image: radial-gradient(circle at 70% 50%, #ffffff 0%, transparent 60%);">
    </div>

    <div class="relative flex flex-col md:flex-row items-center justify-between gap-6 px-8 py-12 md:px-14 md:py-16">

        {{-- Colonne gauche --}}
        <div class="flex flex-col gap-6 flex-1 max-w-xl">

            {{-- Pill badge --}}
            <span
                class="inline-flex items-center gap-2 w-fit bg-white/15 text-white text-xs font-medium px-3 py-1.5 rounded-full">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                Aucune commission sur vos locations
            </span>

            <div>
                <h1 class="text-4xl md:text-6xl font-bold font-display leading-[1.1] text-white">
                    Vivez sereinement
                </h1>
                <h1 class="text-4xl md:text-6xl font-bold font-display leading-[1.1] text-white/70 italic">
                    sans commission.
                </h1>
            </div>

            <p class="text-white/80 text-sm md:text-base leading-relaxed max-w-md">
                Samaritain est l'entreprise qui ne prélève aucun frais sur la location.
                Vous payez votre caution, rien de plus.
            </p>

            {{-- Search bar --}}
            <form action="{{ route('property.search') }}" method="GET" class="relative w-full max-w-lg">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" name="keyword" placeholder="Ville, quartier, type de bien…"
                    class="w-full bg-white rounded-full border-0 pl-11 pr-4 py-3.5 text-sm text-gray-800 shadow-lg placeholder-gray-400 focus:ring-2 focus:ring-white/50 focus:outline-none">
                <button type="submit"
                    class="absolute inset-y-0 right-1.5 my-1.5 px-4 bg-primary text-white text-xs font-semibold rounded-full hover:opacity-90 transition">
                    Rechercher
                </button>
            </form>

            {{-- Micro-stats --}}
            <div class="flex items-center gap-6 pt-2">
                <div>
                    <p class="text-white font-bold text-lg leading-tight">{{ $properties->count() }}+</p>
                    <p class="text-white/60 text-xs">biens disponibles</p>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div>
                    <p class="text-white font-bold text-lg leading-tight">0%</p>
                    <p class="text-white/60 text-xs">de commission</p>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div>
                    <p class="text-white font-bold text-lg leading-tight">24h</p>
                    <p class="text-white/60 text-xs">traitement dossier</p>
                </div>
            </div>
        </div>

        {{-- Colonne droite — image flottante --}}
        <div class="relative flex-shrink-0 hidden md:block">
            <div class="absolute -inset-4 bg-white/10 rounded-3xl blur-2xl"></div>
            <img src="https://media.istockphoto.com/id/1165384568/fr/photo/complexe-moderne-europ%C3%A9en-de-b%C3%A2timents-r%C3%A9sidentiels.jpg?s=612x612&w=0&k=20&c=nvoIbiIffCt-nuj47Cc3I261Ke98iMouq_HefNM7Lz0="
                alt="Résidence moderne" class="relative w-80 lg:w-96 rounded-2xl object-cover shadow-2xl"
                style="height: 280px;">
            {{-- Badge flottant --}}
            <div class="absolute -bottom-3 -left-4 bg-white rounded-xl px-3 py-2 shadow-lg flex items-center gap-2">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-800">Bien vérifié</p>
                    <p class="text-xs text-gray-500">Visite disponible</p>
                </div>
            </div>
        </div>

    </div>
</header>
