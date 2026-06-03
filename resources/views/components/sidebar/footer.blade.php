@props([
    'name' => '',
    'email' => '',
    'avatar' => '',
])

<div class="h-14 border-t border-[var(--sidebar-border)] flex items-center px-3 gap-2 justify-between shrink-0 bg-[var(--sidebar)]/80 mt-auto">
    <div class="flex items-center gap-2 overflow-hidden w-full">
        <!-- User avatar -->
        @if ($avatar)
        <img src="{{ $avatar }}" alt="{{ $name }}"
            class="w-7 h-7 rounded-md shrink-0 object-cover border border-zinc-700 shadow-sm"
            onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop'">
        @else
            <div class="w-7 h-7 rounded-md shrink-0 bg-zinc-700 flex items-center justify-center text-xs font-medium text-white">
                {{ strtoupper(substr($name, 0, 2)) }}
            </div>  
        @endif

        <!-- Text labels (hidden when collapsed) -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex flex-col text-left overflow-hidden flex-1 select-none">
            <span class="text-xs font-semibold text-[var(--sidebar-accent-foreground)] truncate leading-tight">{{ $name }}</span>
            <span class="text-[10px] text-[var(--sidebar-accent-foreground)] truncate leading-tight">{{ $email }}</span>
        </div>
    </div>

    <!-- User options menu icon (hidden when collapsed) -->
    <div x-show="sidebarOpen" class="text-[var(--sidebar-accent-foreground)] shrink-0">
        <i data-lucide="chevrons-up-down" height="16" width="16"></i>
    </div>
</div>
