@props([
    'name' => 'Samaritain Immobilier',
    'role' => 'Admin',
])

<div class="h-14 border-b border-[var(--sidebar-border)] flex items-center px-3 gap-2 justify-between shrink-0 bg-[var(--sidebar)]"
    x-data="{ dropdownOpen: false }">
    <div class="flex items-center gap-2 overflow-hidden w-full">
        <!-- Logo block -->
        <div>
            <img src="{{ asset('light_logo.svg') }}" alt="light logo" class="w-10 py-3 block dark:hidden">
            <img src="{{ asset('dark_logo.svg') }}" alt="dark logo" class="w-10 py-3 dark:block hidden">
        </div>

        <!-- Text labels (hidden when collapsed) -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex flex-col text-left overflow-hidden select-none cursor-pointer flex-1">
            <span
                class="text-xs font-semibold text-[var(--sidebar-accent-foreground)] truncate leading-tight">{{ $name }}</span>
            <span
                class="text-[10px] text-[var(--sidebar-accent-foreground)] truncate leading-tight">{{ $role }}</span>
        </div>
    </div>
</div>
