@props([
    'property' => $property
])

@if ($property->surface || $property->rooms || $property->bedrooms || $property->bathrooms || $property->floor)
    <div
        class="grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))] bg-sidebar rounded-xl
                                overflow-hidden border border-[#ECE8E1] divide-x divide-[#ECE8E1] mb-10">

        @if ($property->surface)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="ruler" class="text-primary"></i>
                <span class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] leading-none">
                    {{ $property->surface }}<small class="text-[0.8rem] font-body font-normal">
                        m²</small>
                </span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660]">Surface</span>
            </div>
        @endif

        @if ($property->rooms)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="home" class="text-primary"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] leading-none">{{ $property->rooms }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660]">Pièces</span>
            </div>
        @endif

        @if ($property->bedrooms)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="bed" class="text-primary"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] leading-none">{{ $property->bedrooms }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660]">Chambres</span>
            </div>
        @endif

        @if ($property->bathrooms)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="bath" class="text-primary"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] leading-none">{{ $property->bathrooms }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660]">SDB</span>
            </div>
        @endif

        @if ($property->floor)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="footprints" class="text-primary"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] leading-none">{{ $property->floor }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660]">Étage</span>
            </div>
        @endif
    </div>
@endif
