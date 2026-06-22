@props([
    'property' => $property
])

@php
    $amenityIcons = [
        'wifi' => 'wifi',
        'internet' => 'wifi',
        'parkings' => 'car',
        'garage' => 'car-front',
        'climatisation' => 'air-vent',
        'air conditionné' => 'air-vent',
        'cuisine' => 'utensils-crossed',
        'piscine' => 'waves',
        'jardin' => 'trees',
        'terrasse' => 'sun',
        'balcon' => 'building',
        'sécurité' => 'shield-check',
        'gardiennage' => 'shield-check',
        'caméra' => 'cctv',
        'eau' => 'droplets',
        'électricité' => 'zap',
        'tv' => 'tv',
        'télévision' => 'tv',
        'ascenseur' => 'move-vertical',
        'meublé' => 'sofa',
        'chauffage' => 'flame',
        'buanderie' => 'washing-machine',
        'salle de sport' => 'dumbbell',
        'gym' => 'dumbbell',
    ];
@endphp

<section class="mb-11">
    <div class="flex items-center gap-3 mb-5">
        <h2 class="font-display font-semibold text-2xl text-secondary dark:text-gray-200">
            Équipements & services
        </h2>
        <span class="flex-1 h-px bg-[#ECE8E1] dark:bg-gray-700"></span>
    </div>

    <ul class="divide-y divide-[#ECE8E1] dark:divide-gray-700 rounded-xl border border-[#ECE8E1] dark:border-gray-700 overflow-hidden bg-sidebar dark:bg-gray-800">
        @foreach ($property->amenities as $amenity)
            @php
                $icon = $amenityIcons[strtolower($amenity->name)] ?? 'check-circle-2';
            @endphp

            <li class="flex items-center justify-between px-5 py-4 hover:bg-accent dark:hover:bg-gray-700/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-lg text-primary dark:text-primary-400">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                    </div>

                    <span class="font-body text-sm font-medium text-[#0F0E0C] dark:text-white">
                        {{ $amenity->name }}
                    </span>
                </div>
            </li>
        @endforeach
    </ul>
</section>