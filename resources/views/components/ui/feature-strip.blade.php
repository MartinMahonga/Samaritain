@props([
    'property' => $property
])

@if ($property->surface || $property->rooms || $property->bedrooms || $property->bathrooms || $property->floor)
    <div
        class="grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))] bg-sidebar dark:bg-gray-800 rounded-xl
                                overflow-hidden border border-[#ECE8E1] dark:border-gray-700 divide-x divide-[#ECE8E1] dark:divide-gray-700 mb-10">

        @if ($property->surface)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="ruler" class="text-primary dark:text-primary-400"></i>
                <span class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] dark:text-white leading-none">
                    {{ $property->surface }}<small class="text-[0.8rem] font-body font-normal dark:text-gray-400">
                        m²</small>
                </span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660] dark:text-gray-400">Surface</span>
            </div>
        @endif

        @if ($property->rooms)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="home" class="text-primary dark:text-primary-400"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] dark:text-white leading-none">{{ $property->rooms }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660] dark:text-gray-400">Pièces</span>
            </div>
        @endif

        @if ($property->bedrooms)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="bed" class="text-primary dark:text-primary-400"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] dark:text-white leading-none">{{ $property->bedrooms }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660] dark:text-gray-400">Chambres</span>
            </div>
        @endif

        @if ($property->bathrooms)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="bath" class="text-primary dark:text-primary-400"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] dark:text-white leading-none">{{ $property->bathrooms }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660] dark:text-gray-400">SDB</span>
            </div>
        @endif

        @if ($property->floor)
            <div class="flex flex-col items-center gap-1 px-4 py-5 text-center">
                <i data-lucide="footprints" class="text-primary dark:text-primary-400"></i>
                <span
                    class="font-display font-semibold text-[1.4rem] text-[#0F0E0C] dark:text-white leading-none">{{ $property->floor }}</span>
                <span class="text-[0.68rem] font-medium tracking-widest uppercase text-[#6B6660] dark:text-gray-400">Étage</span>
            </div>
        @endif
    </div>
@endif