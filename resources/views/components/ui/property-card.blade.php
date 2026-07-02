@props([
    'property' => null,
])

<a href="{{ route('property.show', ['property' => $property->id]) }}"
    class="group shrink-0 sm:42 44 snap-start cursor-pointer">

    {{-- Image --}}
    <div class="relative overflow-hidden rounded-2xl bg-gray-100" style="height: 176px;">
        <img src="{{ $property->images->first()->image_url }}" alt="{{ $property->title }}"
            class="w-full h-full object-cover shadow-sm transition duration-500 group-hover:scale-105">

        {{-- Overlay gradient --}}
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-100 md:opacity-0 md:group-hover:opacity-100 transition duration-300">
            @if (auth()->check())
                <div x-data="{
                    favorited: {{ auth()->user()->favorites->contains($property->id) ? 'true' : 'false' }},
                    async toggle() {
                
                        const response = await fetch(
                            '{{ route('property.favorite', $property) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document
                                        .querySelector('meta[name=csrf-token]')
                                        .content,
                                    'Accept': 'application/json'
                                }
                            }
                        );
                
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

        @if ($property->is_verify)
            <span
                class="absolute bottom-2.5 left-2.5 flex items-center gap-1 bg-white/90 backdrop-blur-sm text-green-600 text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">
                <i data-lucide="badge-check" class="w-3 h-3"></i>
                vérifié
            </span>
        @else
            <span
                class="absolute bottom-2.5 left-2.5 flex items-center gap-1 bg-white/90 backdrop-blur-sm text-amber-600 text-xs font-medium px-2.5 py-1 rounded-full shadow-sm">
                <i data-lucide="hourglass" class="w-3 h-3"></i>
                en attente
            </span>
        @endif

        {{-- Affichage du nombre de vues sur l'image --}}
        <div class="absolute bottom-2.5 right-2.5 flex items-center gap-1 bg-black/50 backdrop-blur-sm text-white text-xs font-medium px-2 py-1 rounded-full shadow-sm">
            <i data-lucide="eye" class="w-3 h-3"></i>
            {{ number_format($property->views) }}
        </div>
    </div>

    {{-- Infos --}}
    <div class="mt-3 space-y-1.5 px-0.5">
        <div class="flex items-start justify-between gap-2">
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-xs text-gray-800 dark:text-gray-300 truncate group-hover:text-primary transition">
                        {{ $property->title }}
                    </h3>
                    <div class="text-gray-400 text-xs flex items-center gap-1">
                        <i data-lucide="land-plot" class="h-3 w-3"></i>
                        {{ $property->surface }}m²
                    </div>
                </div>
                <p class="text-gray-400 text-xs flex items-center gap-1 mt-0.5">
                    <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0"></i>
                    {{ $property->city->name }}
                </p>
            </div>
        </div>

        <p class="text-gray-500 text-xs leading-relaxed line-clamp-1">
            {{ $property->description }}
        </p>

        <div class="flex items-center justify-between pt-1">
            <p class="text-gray-800 dark:text-gray-300 text-xs font-bold">
                {{ number_format($property->price, 0, ',', ' ') }}
                <span class="text-xs font-normal text-gray-400">FCFA / mois</span>
            </p>
            <div class="flex items-center gap-3">
                {{-- Affichage alternatif des vues dans la partie inférieure --}}
                <span class="text-xs text-gray-400 flex items-center gap-1">
                    <i data-lucide="eye" class="w-3 h-3"></i>
                    {{ number_format($property->views) }}
                </span>
                <span
                    class="text-xs text-primary font-medium opacity-0 group-hover:opacity-100 transition flex items-center gap-0.5">
                    Voir
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </span>
            </div>
        </div>
    </div>
</a>