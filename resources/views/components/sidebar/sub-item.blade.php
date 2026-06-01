@props([
    'label' => '',
    'href' => '#',
    'active' => false
])

<a href="{{ $href }}"
    class="block py-1.5 px-3 rounded-md text-[11px] font-medium transition-colors select-none truncate
        {{ $active ? 'text-[var(--sidebar-primary-foreground)] bg-[var(--sidebar-primary)]/20 font-semibold' : 'text-[var(--sidebar-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)]/70' }}">
    {{ $label }}
</a>
