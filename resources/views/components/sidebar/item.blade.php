@props([
    'icon' => '',
    'label' => '',
    'href' => '#',
    'active' => false,
    'expanded' => false,
])

@php
    $hasSubItems = $slot->isNotEmpty();
@endphp

<li x-data="{ open: @json($expanded) }" class="relative list-none">

    @if ($hasSubItems)
        <!-- Collapsible Menu Toggle Trigger -->
        <button x-on:click="open = !open; if(!sidebarOpen) sidebarOpen = true"
            class="w-full flex items-center px-3 py-2 rounded-md text-xs font-medium transition-all group select-none text-left justify-between focus:outline-none
                {{ $active ? 'bg-[var(--sidebar-primary)] text-[var(--sidebar-primary-foreground)]' : 'text-[var(--sidebar-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)]' }}">

            <div class="flex items-center gap-2 overflow-hidden">
                <!-- Icon -->
                @if ($icon)
                    <span class="shrink-0 flex items-center justify-center w-4 h-4">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4 transition-colors"></i>
                    </span>
                @endif

                <!-- Label text -->
                <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-1"
                    x-transition:enter-end="opacity-100 translate-x-0" class="truncate">{{ $label }}</span>
            </div>

            <!-- Submenu Indicators (hidden when collapsed) -->
            <div x-show="sidebarOpen" class="shrink-0 text-[var(--sidebar-foreground)] transition-transform duration-200"
                :class="{ 'rotate-90': open }">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </div>
        </button>

        <!-- Submenu panel (expanded state only) -->
        <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 max-h-0 overflow-hidden" x-transition:enter-end="opacity-100 max-h-40"
            class="pl-6 pr-2 py-1 flex flex-col gap-0.5 border-l border-[var(--sidebar-border)] ml-5 mt-0.5">
            {{ $slot }}
        </div>
    @else
        <!-- Regular Link Button -->
        <a href="{{ $href }}"
            class="flex items-center px-3 py-2 rounded-md text-xs font-medium transition-all group select-none
                {{ $active ? 'bg-[var(--sidebar-primary)] text-[var(--sidebar-primary-foreground)]' : 'text-[var(--sidebar-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)]' }}">

            <!-- Icon -->
            @if ($icon)
                <span class="shrink-0 flex items-center justify-center w-4 h-4">
                    <i data-lucide="{{ $icon }}" class="w-4 h-4 transition-colors"></i>
                </span>
            @endif

            <!-- Label text -->
            <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-x-1" x-transition:enter-end="opacity-100 translate-x-0"
                class="ml-2 truncate">{{ $label }}</span>
        </a>
    @endif
</li>
