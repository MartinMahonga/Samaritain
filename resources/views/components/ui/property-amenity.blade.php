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
        <h2 class="font-display font-semibold text-2xl text-secondary">
            Équipements & services
        </h2>
        <span class="flex-1 h-px bg-[#ECE8E1]"></span>
    </div>

    <ul class="divide-y divide-[#ECE8E1] rounded-xl border border-[#ECE8E1] overflow-hidden bg-sidebar">
        @foreach ($property->amenities as $amenity)
            @php
                $icon = $amenityIcons[strtolower($amenity->name)] ?? 'check-circle-2';
            @endphp

            <li class="flex items-center justify-between px-5 py-4 hover:bg-accent transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-lg text-primary">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                    </div>

                    <span class="font-body text-sm font-medium text-[#0F0E0C]">
                        {{ $amenity->name }}
                    </span>
                </div>

                <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
            </li>
        @endforeach
    </ul>
</section>