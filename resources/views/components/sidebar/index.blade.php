<aside :class="sidebarOpen ? 'w-64' : 'w-14'"
    class="h-screen bg-[var(--sidebar)] border-r border-[var(--sidebar-border)] flex flex-col shrink-0 overflow-x-hidden transition-all duration-300 ease-in-out select-none">
    {{ $slot }}
</aside>
