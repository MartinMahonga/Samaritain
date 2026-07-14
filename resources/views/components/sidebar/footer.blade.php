@props([
    'name' => '',
    'email' => '',
    'avatar' => '',
])

<div x-data="{ open: false }" x-on:click="open = !open" @keydown.escape="open = false" type="button" aria-haspopup="true"
    :aria-expanded="open.toString()"
    class="h-14 border-t border-[var(--sidebar-border)] flex items-center px-3 gap-2 justify-between shrink-0 bg-[var(--sidebar)]/80 mt-auto cursor-pointer">
    <div class="flex items-center gap-2 overflow-hidden w-full">
        <!-- User avatar -->
        @if ($avatar)
            <img src="{{ $avatar }}" alt="{{ $name }}"
                class="w-7 h-7 rounded-md shrink-0 object-cover border border-gray-200 shadow-sm"
                onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop'">
        @else
            <div
                class="w-7 h-7 rounded-md shrink-0 bg-zinc-700 flex items-center justify-center text-xs font-medium text-white">
                {{ strtoupper(substr($name, 0, 2)) }}
            </div>
        @endif

        <!-- Text labels (hidden when collapsed) -->
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="flex flex-col text-left overflow-hidden flex-1 select-none">
            <span
                class="text-xs font-semibold text-[var(--sidebar-accent-foreground)] truncate leading-tight">{{ $name }}</span>
            <span
                class="text-[10px] text-[var(--sidebar-accent-foreground)] truncate leading-tight">{{ $email }}</span>
        </div>
    </div>

    <!-- User options dropdown (hidden when collapsed) -->
    <div x-show="sidebarOpen" class="shrink-0" x-cloak>
        <div class="relative text-[var(--sidebar-accent-foreground)]">
            <button class="p-1 rounded-md hover:bg-white/5">
                <i data-lucide="chevrons-up-down" height="16" width="16"></i>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1" x-on:click.away="open = false"
                @keydown.escape.window="open = false" x-cloak
                class="origin-bottom-right absolute right-0 bottom-full mb-2 w-48 bg-[var(--sidebar)] border border-[var(--sidebar-border)] rounded-md shadow-lg z-50 overflow-hidden flex flex-col gap-2 p-1">
                <a href="#"
                    class="flex gap-1 text-sm text-[var(--sidebar-accent-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)] px-2 py-2 rounded-md">
                    <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                    Profil
                </a>
                <a href="#"
                    class="flex gap-1 text-sm text-[var(--sidebar-accent-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)] px-2 py-2 rounded-md">
                    <i data-lucide="settings" class="w-4 h-4 mr-2"></i>
                    Paramètres
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex gap-1 text-left text-sm text-[var(--sidebar-accent-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)] px-2 py-2 rounded-md">
                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
