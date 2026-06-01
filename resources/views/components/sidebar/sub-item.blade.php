@props([
    'label' => '',
    'href' => '#',
    'active' => false
])

<a href="{{ $href }}"
    class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors select-none truncate
        {{ $active ? 'text-zinc-100 bg-zinc-800/40 font-semibold' : 'text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900/40' }}">
    {{ $label }}
</a>
