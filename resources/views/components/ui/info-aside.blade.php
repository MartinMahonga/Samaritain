@props([
    'property' => $property
])

<aside>
    <div class="rounded-xl p-8 text-secondary lg:sticky lg:top-8">

        {{-- Price --}}
        <div>
            <p class="font-body text-[0.68rem] font-medium tracking-[0.12em] uppercase mb-1.5">
                {{ $property->price_type === 'monthly' ? 'Loyer mensuel' : 'Prix de vente' }}
            </p>
            @if ($property->price)
                <p class="font-display font-bold text-[2.6rem] text-primary leading-none mb-1">
                    {{ number_format($property->price, 0, ',', ' ') }}
                    <sub class="font-body text-[0.75rem] font-normal text-primary/90 align-middle ml-1">
                        FCFA{{ $property->price_type === 'monthly' ? '/mois' : '' }}
                    </sub>
                </p>
            @endif
            <div class="w-8 h-0.5 bg-primary rounded-full my-5"></div>
        </div>

        {{-- Location --}}
        <div
            class="flex items-center gap-2 font-body text-[0.8rem] px-4 py-3 rounded-xl mb-6">
            <i data-lucide="map-pin" class="text-primary w-5 h-5"></i>
            {{ $property->address ?? '' }}{{ $property->address && $property->city ? ', ' : '' }}{{ $property->city->name ?? 'Brazzaville' }}
        </div>

        {{-- Map placeholder --}}
        <div
            class="w-full h-36 border border-secondary/10 rounded-xl
                flex flex-col items-center justify-center gap-2
                font-body text-[0.75rem] mb-6
                cursor-pointer transition-colors duration-200">
            <i data-lucide="map" class="text-primary"></i>
            <span>Voir sur la carte</span>
        </div>

        {{-- CTAs --}}
        <div class="flex flex-col gap-2.5 mb-4">
            <x-btn>Contacter l'agence</x-btn>
            <x-btn style="secondary">Reserver une visite</x-btn>
        </div>

        {{-- Agent --}}
        @if ($property->agent)
            <div class="flex items-center gap-3.5 pt-5 border-t border-secondary/10 mt-6">
                <div
                    class="w-11 h-11 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                    @if ($property->agent->avatar)
                        <img src="{{ $property->agent->avatar }}" alt="{{ $property->agent->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" class="w-4.5 h-4.5 text-primary">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    @endif
                </div>
                <div>
                    <div class="font-body text-[0.85rem] font-semibold">
                        {{ $property->agent->name }}</div>
                    <div class="font-body text-[0.72rem] mt-0.5">Agent immobilier</div>
                </div>
            </div>
        @endif

    </div>

    @if ($property->reference)
        <p class="font-body text-[0.7rem] text-[#C4C0BA] text-center mt-2.5">
            Réf. {{ $property->reference }}
        </p>
    @endif
</aside>
