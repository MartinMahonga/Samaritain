@props(['parcelle'])

@php
    $imagePrincipale =
        collect($parcelle['images'] ?? [])->firstWhere('principale', true) ??
        collect($parcelle['images'] ?? [])->first();

    $statutConfig = [
        'disponible' => [
            'label' => 'Disponible',
            'class' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
        ],
        'vendu' => ['label' => 'Vendu', 'class' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'],
        'réservé' => [
            'label' => 'Réservé',
            'class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
        ],
    ];

    $statut = $statutConfig[$parcelle['statut']] ?? $statutConfig['disponible'];

    $prix = number_format($parcelle['prix'], 0, ',', ' ') . ' FCFA';
    $superficie = number_format($parcelle['superficie'], 0, ',', ' ') . ' m²';
@endphp

<a href="{{ route('parcelles.show', $parcelle) }}"
    class="group shrink-0 snap-start w-44 md:w-52 cursor-pointer block transition-all duration-300 overflow-hidden">

    {{-- Image --}}
    <div class="relative overflow-hidden" style="height: 176px;">
        @if ($imagePrincipale)
            <img src="{{ $imagePrincipale['url'] }}" alt="{{ $parcelle['titre'] }}"
                class="w-full h-full object-cover rounded-2xl shadow-sm transition duration-500 group-hover:scale-105"
                onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center text-gray-400\'><svg xmlns=\'http://www.w3.org/2000/svg\' class=\'w-12 h-12\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M3 9.75L12 3l9 6.75V21H3V9.75z\'/></svg><span class=\'text-sm mt-2\'>Pas d\'image</span></div>'">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                </svg>
                <span class="text-sm mt-2">Pas d'image</span>
            </div>
        @endif

        {{-- Overlay gradient --}}
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-100 md:opacity-0 md:group-hover:opacity-100 transition duration-300">
            {{-- Bouton favoris (optionnel) --}}
            @if (auth()->check())
                <div x-data="{
                    favorited: {{ $parcelle->isFavorited() ? 'true' : 'false' }},
                    async toggle() {
                        const response = await fetch('{{ route('parcel.favorite', $parcelle) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        this.favorited = data.favorited;
                    }
                }" class="absolute top-3 right-3 z-20">
                    <button @click.prevent="toggle"
                        class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-md hover:scale-110 transition">
                        <i data-lucide="heart" class="w-5 h-5"
                            :class="favorited ? 'fill-red-500 text-red-500' : 'text-gray-500'"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Badge statut --}}
        <span
            class="absolute top-3 left-3 text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm {{ $statut['class'] }}">
            {{ $statut['label'] }}
        </span>

        {{-- Badge viabilisée --}}
        @if ($parcelle['viabilisee'])
            <span
                class="absolute bottom-2.5 left-2.5 flex items-center gap-1 bg-white/90 backdrop-blur-sm text-blue-600 text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">
                <i data-lucide="check-circle" class="w-3 h-3"></i>
                Viabilisée
            </span>
        @endif

        {{-- Vues --}}
        <div
            class="absolute bottom-2.5 right-2.5 flex items-center gap-1 bg-black/50 backdrop-blur-sm text-white text-xs font-medium px-2 py-1 rounded-full shadow-sm">
            <i data-lucide="eye" class="w-3 h-3"></i>
            {{ number_format($parcelle['views'] ?? 0) }}
        </div>
    </div>

    {{-- Contenu --}}
    <div class="p-4 space-y-1.5">
        <div class="flex items-start justify-between gap-2">
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h3
                        class="font-semibold text-sm text-gray-800 dark:text-gray-200 truncate group-hover:text-primary transition">
                        {{ $parcelle['titre'] }}
                    </h3>
                    <div class="text-gray-400 text-xs flex items-center gap-1 shrink-0 ml-2">
                        <i data-lucide="land-plot" class="h-3 w-3"></i>
                        {{ $superficie }}
                    </div>
                </div>
                <p class="text-gray-400 text-xs flex items-center gap-1 mt-0.5">
                    <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0"></i>
                    {{ $parcelle['quartier'] }}, {{ $parcelle['ville'] }}
                </p>
            </div>
        </div>

        <p class="text-gray-500 text-xs leading-relaxed line-clamp-1">
            {{ $parcelle['description'] ?? '' }}
        </p>

        <div class="flex items-center justify-between">
            <p class="text-gray-800 dark:text-gray-200 text-sm font-bold">
                {{ $prix }}
                <span class="text-xs font-normal text-gray-400"></span>
            </p>
            <div class="flex items-center gap-3">
                <span
                    class="text-xs text-primary font-medium opacity-0 group-hover:opacity-100 transition flex items-center gap-0.5">
                    Voir
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </span>
            </div>
        </div>
    </div>
</a>
