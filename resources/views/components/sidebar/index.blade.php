<aside :class="sidebarOpen ? 'w-64' : 'w-14'"
    class="h-screen bg-zinc-950 border-r border-zinc-800 flex flex-col shrink-0 overflow-x-hidden transition-all duration-300 ease-in-out select-none">
    {{ $slot }}
</aside>
