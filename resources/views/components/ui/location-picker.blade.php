@props([
    'latitude' => null,
    'longitude' => null,
    'defaultLat' => -4.2634,
    'defaultLng' => 15.2429,
    'defaultZoom' => 13,
])

@php
    $lat = old('latitude', $latitude);
    $lng = old('longitude', $longitude);
    $hasCoords = ! is_null($lat) && $lat !== '' && ! is_null($lng) && $lng !== '';
@endphp

<div
    x-data="locationPicker({
        lat: @js($hasCoords ? (float) $lat : null),
        lng: @js($hasCoords ? (float) $lng : null),
        defaultLat: {{ $defaultLat }},
        defaultLng: {{ $defaultLng }},
        defaultZoom: {{ $defaultZoom }},
    })"
    x-init="init($dispatch)"
    class="space-y-3"
>
    {{-- Hidden fields for the form --}}
    <input type="hidden" name="latitude" x-model="latitude" :value="latitude" />
    <input type="hidden" name="longitude" x-model="longitude" :value="longitude" />

    {{-- Coordonnées affichées --}}
    <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
        <span x-show="latitude !== null && latitude !== ''" x-cloak>
            Lat : <span class="font-mono text-gray-800 dark:text-gray-200" x-text="latitude"></span>
        </span>
        <span x-show="longitude !== null && longitude !== ''" x-cloak>
            Lng : <span class="font-mono text-gray-800 dark:text-gray-200" x-text="longitude"></span>
        </span>
        <span x-show="!latitude || latitude === ''" class="text-gray-400 dark:text-gray-500">
            Aucune position sélectionnée — cliquez sur la carte pour placer le marqueur.
        </span>
    </div>

    {{-- Carte --}}
    <div
        id="location-picker-map-{{ $attributes->get('id', 'picker') }}"
        class="w-full h-[350px] rounded-xl overflow-hidden border border-gray-300 dark:border-gray-700 z-0"
    ></div>

    {{-- Indice --}}
    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
        <i data-lucide="info" class="w-3.5 h-3.5"></i>
        Cliquez sur la carte pour placer le marqueur. Vous pouvez aussi le glisser-déposer.
    </p>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    function locationPicker(config) {
        return {
            latitude: config.lat,
            longitude: config.lng,
            map: null,
            marker: null,

            init($dispatch) {
                const mapId = 'location-picker-map-picker';

                this.map = L.map(mapId).setView(
                    this.latitude && this.longitude
                        ? [this.latitude, this.longitude]
                        : [config.defaultLat, config.defaultLng],
                    this.latitude && this.longitude ? 15 : config.defaultZoom
                );

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(this.map);

                // Si des coordonnées existent déjà, placer le marqueur
                if (this.latitude && this.longitude) {
                    this.placeMarker(this.latitude, this.longitude);
                }

                // Clic sur la carte → placer/déplacer le marqueur
                this.map.on('click', (e) => {
                    this.placeMarker(e.latlng.lat, e.latlng.lng);
                });

                // Forcer Leaflet à recalculer après que le DOM soit stable
                setTimeout(() => this.map.invalidateSize(), 100);
            },

            placeMarker(lat, lng) {
                this.latitude = parseFloat(lat.toFixed(6));
                this.longitude = parseFloat(lng.toFixed(6));

                if (this.marker) {
                    this.marker.setLatLng([lat, lng]);
                } else {
                    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                    this.marker.on('dragend', () => {
                        const pos = this.marker.getLatLng();
                        this.latitude = parseFloat(pos.lat.toFixed(6));
                        this.longitude = parseFloat(pos.lng.toFixed(6));
                    });
                }

                this.map.setView([lat, lng], this.map.getZoom());
            },
        };
    }
</script>