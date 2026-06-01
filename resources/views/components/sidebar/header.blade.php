@props([
    'name' => 'Acme Inc',
    'plan' => 'Enterprise',
])

<div class="h-14 border-b border-zinc-800 flex items-center px-3 gap-2 justify-between shrink-0 bg-zinc-900/40"
    x-data="{ dropdownOpen: false }">
    <div class="flex items-center gap-2 overflow-hidden w-full">
        <!-- Logo block -->
        <div
            class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shrink-0 shadow-md shadow-indigo-600/10">
            <!-- Sleek minimalist building/workspace icon -->
            <i data-lucide="command" class="w-4 h-4 text-white"></i>
        </div>

        <!-- Text labels (hidden when collapsed) -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex flex-col text-left overflow-hidden select-none cursor-pointer flex-1">
            <span class="text-xs font-semibold text-zinc-100 truncate leading-tight">{{ $name }}</span>
            <span class="text-[10px] text-zinc-400 truncate leading-tight">{{ $plan }}</span>
        </div>
    </div>

    <!-- Workspace selector dropdown arrow (hidden when collapsed) -->
    <div x-show="sidebarOpen" class="text-zinc-400 shrink-0">
        <i data-lucide="chevrons-up-down" class="w-3.5 h-3.5"></i>
    </div>
</div>
