@props([
    'property' => null,
    'latitude' => null,
    'longitude' => null,
    'title' => null,
])

@php
    $lat = $latitude ?? ($property?->latitude ?? null);
    $lng = $longitude ?? ($property?->longitude ?? null);
    $label = $title ?? ($property?->title ?? 'Bien immobilier');
    $hasCoords = ! is_null($lat) && ! is_null($lng);
@endphp

@if ($hasCoords)
    <div class="w-full">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

        <div
            {{ $attributes->merge(['class' => 'w-full h-[400px] rounded-xl overflow-hidden z-0']) }}
            id="property-map"
        ></div>

        <div class="mt-3 flex flex-wrap gap-3">
            <a
                href="https://www.google.com/maps/search/?api=1&query={{ $lat }},{{ $lng }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-x-1.5 shrink-0 transition-colors duration-100 text-sm/5 font-medium shadow-none rounded-[var(--radius)] border border-[var(--border)] dark:border-gray-700 text-[var(--foreground)] dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 h-9 px-4 py-2"
            >
                <i data-lucide="map" class="w-4 h-4"></i>
                Ouvrir dans Google Maps
            </a>
            <a
                href="https://www.google.com/maps/dir/?api=1&destination={{ $lat }},{{ $lng }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-x-1.5 shrink-0 transition-colors duration-100 text-sm/5 font-medium shadow-none rounded-[var(--radius)] bg-[var(--primary)] text-[var(--primary-foreground)] hover:bg-[color-mix(in_oklab,var(--primary)_90%,transparent)] h-9 px-4 py-2"
            >
                <i data-lucide="navigation" class="w-4 h-4"></i>
                Itinéraire
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function () {
            var map = L.map('property-map').setView([{{ $lat }}, {{ $lng }}], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            L.marker([{{ $lat }}, {{ $lng }}])
                .addTo(map)
                .bindPopup(@json($label))
                .openPopup();
        })();
    </script>
@else
    <div
        {{ $attributes->merge(['class' => 'w-full h-[400px] rounded-xl border border-secondary/10 dark:border-gray-700 flex flex-col items-center justify-center gap-2 font-body text-[0.8rem] text-[#6B6660] dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30']) }}
    >
        <i data-lucide="map-pin-off" class="text-primary dark:text-primary-400 w-6 h-6"></i>
        <span>La localisation de cette propriété n'est pas disponible.</span>
    </div>
@endif