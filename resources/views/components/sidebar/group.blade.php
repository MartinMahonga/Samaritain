@props([
    'label' => '',
])

<div class="px-2 py-2 flex flex-col gap-0.5">
    <!-- Group label (hidden when collapsed) -->
    @if ($label)
        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="text-[10px] font-medium tracking-wider text-[var(--sidebar-accent-foreground)] uppercase px-2 py-1.5 select-none block">
            {{ $label }}
        </span>
    @endif

    <!-- Group Items list -->
    <ul class="flex flex-col gap-0.5">
        {{ $slot }}
    </ul>
</div>
