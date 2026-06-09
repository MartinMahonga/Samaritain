@props([
    'property' => $property
])

<section class="mb-11">
    <div class="flex items-center gap-3 mb-5">
        <h2 class="font-display font-semibold text-2xl text-secondary">Description</h2>
        <span class="flex-1 h-px bg-[#ECE8E1]"></span>
    </div>
    <p class="font-body text-[0.92rem] leading-[1.85] text-secondary/70">{{ $property->description }}</p>
</section>
