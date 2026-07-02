@props([
    'properties' => collect(),
])

@if ($properties->isNotEmpty())
    <section class="mt-16">
        <div class="flex items-center gap-3 mb-8">
            <h2 class="font-display font-semibold text-2xl text-[#0F0E0C] dark:text-white">
                Biens similaires
            </h2>
            <span class="flex-1 h-px bg-[#ECE8E1] dark:bg-gray-700"></span>
        </div>

        <div class="grid gap-3 grid-cols-2 md:grid-cols-5">
            @foreach ($properties as $property)
                <x-ui.property-card :property="$property" />
            @endforeach
        </div>
    </section>
@endif