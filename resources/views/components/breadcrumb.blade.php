@props([
    'items' => []
])

<nav class="flex items-center gap-1.5 text-xs text-zinc-500 font-medium select-none" aria-label="Breadcrumb">
    @foreach ($items as $index => $item)
        @if ($index > 0)
            <!-- Chevron separator -->
            <i data-lucide=""="chevron-right" class="w-3.5 h-3.5 text-zinc-600 shrink-0" />
        @endif

        @if ($loop->last)
            <!-- Active (last) item -->
            <span class="text-zinc-200 font-semibold truncate max-w-[150px] sm:max-w-none">{{ $item }}</span>
        @else
            <!-- Non-active links / parents -->
            <a href="#" class="hover:text-zinc-300 transition-colors truncate max-w-[100px] sm:max-w-none">{{ $item }}</a>
        @endif
    @endforeach
</nav>