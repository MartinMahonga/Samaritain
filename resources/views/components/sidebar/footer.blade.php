@props([
    'name' => 'shadcn',
    'email' => 'm@example.com',
    'avatar' => 'https://github.com/shadcn.png',
])

<div class="h-14 border-t border-zinc-800 flex items-center px-3 gap-2 justify-between shrink-0 bg-zinc-900/40 mt-auto">
    <div class="flex items-center gap-2 overflow-hidden w-full">
        <!-- User avatar -->
        <img src="{{ $avatar }}" alt="{{ $name }}"
            class="w-7 h-7 rounded-md shrink-0 object-cover border border-zinc-700 shadow-sm"
            onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop'">

        <!-- Text labels (hidden when collapsed) -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex flex-col text-left overflow-hidden flex-1 select-none">
            <span class="text-xs font-semibold text-zinc-200 truncate leading-tight">{{ $name }}</span>
            <span class="text-[10px] text-zinc-400 truncate leading-tight">{{ $email }}</span>
        </div>
    </div>

    <!-- User options menu icon (hidden when collapsed) -->
    <div x-show="sidebarOpen" class="text-zinc-400 shrink-0">
        <i data-lucide="chevrons-up-down" height="16" width="16"></i>
    </div>
</div>
