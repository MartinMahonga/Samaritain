@props([
    'property' => $property,
])

<div class="flex flex-col gap-2 mb-10" id="gallery">
    {{-- Hero image --}}
    <div class="relative rounded-2xl overflow-hidden bg-[#ECE8E1] group">
        <img id="main-img" src="{{ $property->images->first()?->image_url }}" alt="{{ $property->title }}"
            class="w-full md:h-[420px] h-64 object-cover block transition-transform duration-700 ease-[cubic-bezier(.25,.46,.45,.94)] group-hover:scale-[1.03]">

        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/45 to-transparent pointer-events-none">
        </div>

        {{-- Price overlay --}}
        @if ($property->price)
            <div class="absolute bottom-6 left-6 z-10 flex items-baseline gap-1.5">
                <span class="font-display font-bold text-[2.2rem] text-white leading-none">
                    {{ number_format($property->price, 0, ',', ' ') }}
                </span>
                <span class="font-body text-[0.75rem] font-normal text-white/70">
                    XAF{{ $property->price_type === 'monthly' ? ' / mois' : '' }}
                </span>
            </div>
        @endif

        {{-- Type badge --}}
        @if ($property->type)
            <span
                class="absolute top-5 right-5 z-10 bg-white/15 backdrop-blur-md border border-white/25
                    text-white font-body text-[0.7rem] font-semibold tracking-widest uppercase
                    px-3 py-1.5 rounded-full">
                {{ $property->type }}
            </span>
        @endif
    </div>

    {{-- Thumbnails --}}
    @if ($property->images->count() > 1)
        <div class="grid grid-cols-5 gap-2">
            @foreach ($property->images as $i => $image)
                <button type="button" onclick="switchImage(this, '{{ $image->image_url }}')"
                    aria-label="Image {{ $i + 1 }}"
                    class="g-thumb relative rounded-lg overflow-hidden aspect-[4/3]
                        transition-transform duration-200 hover:-translate-y-0.5
                        ring-2 ring-transparent ring-offset-0
                        {{ $i === 0 ? 'ring-primary' : 'hover:ring-primary' }}">
                    <img src="{{ $image->image_url }}" alt="" loading="{{ $i > 0 ? 'lazy' : 'eager' }}"
                        class="w-full h-full object-cover block">
                </button>
            @endforeach
        </div>
    @endif
</div>

<script>
    function switchImage(thumb, src) {
        const img = document.getElementById('main-img');
        img.style.opacity = '0';
        img.style.transition = 'opacity .2s';
        setTimeout(() => {
            img.src = src;
            img.style.opacity = '1';
        }, 180);
        document.querySelectorAll('.g-thumb').forEach(t => {
            t.classList.remove('ring-primary');
            t.classList.add('ring-transparent');
        });
        thumb.classList.remove('ring-transparent');
        thumb.classList.add('ring-primary');
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
