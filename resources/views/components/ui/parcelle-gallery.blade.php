@props([
    'parcelle' => $parcelle,
])

@php
    $mainImage = $parcelle->imagePrincipale ?? $parcelle->images->first();
    $images = $parcelle->images;
    if ($mainImage && $images->count() > 0) {
        $images = $images->sortByDesc(function($img) use ($mainImage) {
            return $img->id === $mainImage->id;
        })->values();
    }
@endphp

<div class="flex flex-col gap-2 mb-10" id="gallery">
    {{-- Hero image --}}
    <div class="relative rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 group">
        @if ($mainImage)
            <img id="main-img" src="{{ $mainImage->url }}" alt="{{ $parcelle->titre }}"
                class="w-full md:h-[420px] h-64 object-cover block transition-transform duration-700 ease-[cubic-bezier(.25,.46,.45,.94)] group-hover:scale-[1.03]">

            {{-- Gradient overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/45 to-transparent pointer-events-none">
            </div>

            {{-- Price overlay --}}
            @if ($parcelle->prix)
                <div class="absolute bottom-6 left-6 z-10 flex items-baseline gap-1.5">
                    <span class="font-display font-bold text-[2.2rem] text-white leading-none">
                        {{ number_format($parcelle->prix, 0, ',', ' ') }}
                    </span>
                    <span class="font-body text-[0.75rem] font-normal text-white/70">
                        FCFA
                    </span>
                </div>
            @endif
        @else
            <div class="w-full md:h-[420px] h-64 flex items-center justify-center text-gray-400 bg-gray-100 dark:bg-gray-800">
                <div class="text-center">
                    <i data-lucide="image" class="w-16 h-16 mx-auto mb-2"></i>
                    <span class="text-sm">Aucune image disponible</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Thumbnails --}}
    @if ($images->count() > 1)
        <div class="grid grid-cols-5 gap-2">
            @foreach ($images as $i => $image)
                <button type="button" onclick="switchImage(this, '{{ $image->url }}')"
                    aria-label="Image {{ $i + 1 }}"
                    class="g-thumb relative rounded-lg overflow-hidden aspect-[4/3]
                        transition-transform duration-200 hover:-translate-y-0.5
                        ring-2 ring-transparent ring-offset-0
                        {{ $i === 0 ? 'ring-primary dark:ring-primary-400' : 'hover:ring-primary dark:hover:ring-primary-400' }}">
                    <img src="{{ $image->url }}" alt="" loading="{{ $i > 0 ? 'lazy' : 'eager' }}"
                        class="w-full h-full object-cover block">
                </button>
            @endforeach
        </div>
    @endif
</div>

<script>
    function switchImage(thumb, src) {
        const img = document.getElementById('main-img');
        if (!img) return;
        img.style.opacity = '0';
        img.style.transition = 'opacity .2s';
        setTimeout(() => {
            img.src = src;
            img.style.opacity = '1';
        }, 180);
        document.querySelectorAll('.g-thumb').forEach(t => {
            t.classList.remove('ring-primary', 'dark:ring-primary-400');
            t.classList.add('ring-transparent');
        });
        thumb.classList.remove('ring-transparent');
        thumb.classList.add('ring-primary', 'dark:ring-primary-400');
    }

    document.querySelectorAll('.g-thumb').forEach(thumb => {
        thumb.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                thumb.click();
            }
        });
    });
</script>
