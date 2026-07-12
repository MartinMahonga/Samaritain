@props([
    'property' => $property,
])

<aside>
    <div class="rounded-xl p-8 text-secondary dark:text-gray-200 lg:sticky lg:top-8">

        {{-- Price --}}
        <div>
            <p class="font-body text-[0.68rem] font-medium tracking-[0.12em] uppercase mb-1.5 dark:text-gray-400">
                {{ $property->price_type === 'monthly' ? 'Loyer mensuel' : 'Prix de vente' }}
            </p>
            @if ($property->price)
                <p class="font-display font-bold text-[2.6rem] text-primary dark:text-primary-400 leading-none mb-1">
                    {{ number_format($property->price, 0, ',', ' ') }}
                    <sub
                        class="font-body text-[0.75rem] font-normal text-primary/90 dark:text-primary-300 align-middle ml-1">
                        FCFA{{ $property->price_type === 'monthly' ? '/mois' : '' }}
                    </sub>
                </p>
            @endif
            <div class="w-8 h-0.5 bg-primary dark:bg-primary-400 rounded-full my-5"></div>
        </div>

        {{-- Location --}}
        <div class="flex items-center gap-2 font-body text-[0.8rem] px-4 py-3 rounded-xl mb-6 dark:text-gray-300">
            <i data-lucide="map-pin" class="text-primary dark:text-primary-400 w-5 h-5"></i>
            {{ $property->address ?? '' }}{{ $property->address && $property->city ? ', ' : '' }}{{ $property->city->name ?? 'Brazzaville' }}
        </div>

        {{-- Map placeholder --}}
        <div id="map"
            class="w-full h-36 border border-secondary/10 z-0 dark:border-gray-700 rounded-xl
                flex flex-col items-center justify-center gap-2
                font-body text-[0.75rem] mb-6 dark:text-gray-400
                cursor-pointer transition-colors duration-200
                hover:border-primary/30 dark:hover:border-primary/30">
            <i data-lucide="map" class="text-primary dark:text-primary-400"></i>
            <span>Voir sur la carte</span>
        </div>

        {{-- CTAs --}}
        <div class="flex flex-col gap-2.5 mb-4">
            <a href="{{ route('property.contact.create', $property) }}"
                class="inline-flex w-full items-center justify-center gap-x-1.5 shrink-0 transition-colors duration-100 text-sm/5 font-medium shadow-none rounded-[var(--radius)] bg-[var(--primary)] text-[var(--primary-foreground)] hover:bg-[color-mix(in_oklab,var(--primary)_90%,transparent)] focus:bg-[color-mix(in_oklab,var(--primary)_90%,transparent)] active:bg-[var(--primary)] h-9 text-center px-4 py-2">
                Contacter l'entreprise
            </a>
            <a href="{{ route('my-visit-passes.create', $property) }}"
                class="inline-flex w-full items-center justify-center gap-x-1.5 shrink-0 transition-colors duration-100 text-sm/5 font-medium shadow-none rounded-[var(--radius)] border border-[var(--border)] dark:border-gray-700 text-[var(--foreground)] dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 h-9 text-center px-4 py-2">
                <i data-lucide="ticket" class="w-4 h-4"></i>
                Acheter un pass visite
            </a>
        </div>

        {{-- Agent --}}
        @if ($property->agent)
            <div class="flex items-center gap-3.5 pt-5 border-t border-secondary/10 dark:border-gray-700 mt-6">
                <div
                    class="w-11 h-11 rounded-full flex items-center justify-center overflow-hidden shrink-0 bg-gray-100 dark:bg-gray-700">
                    @if ($property->agent->avatar)
                        <img src="{{ $property->agent->avatar }}" alt="{{ $property->agent->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" class="w-4.5 h-4.5 text-primary dark:text-primary-400">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    @endif
                </div>
                <div>
                    <div class="font-body text-[0.85rem] font-semibold dark:text-white">
                        {{ $property->agent->name }}</div>
                    <div class="font-body text-[0.72rem] mt-0.5 dark:text-gray-400">Agent immobilier</div>
                </div>
            </div>
        @endif

    </div>

    @if ($property->reference)
        <p class="font-body text-[0.7rem] text-[#C4C0BA] dark:text-gray-500 text-center mt-2.5">
            Réf. {{ $property->reference }}
        </p>
    @endif
</aside>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([-4.2634, 15.2429], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    L.marker([-4.2634, 15.2429])
        .addTo(map)
        .bindPopup('Bien immobilier');
</script>
