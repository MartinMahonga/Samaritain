<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | Dashboard Samaritain</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--muted);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--muted-foreground);
        }
    </style>
</head>
@php
    $sidebarHtml = (string) $sidebar;
@endphp

<body class="bg-background text-foreground font-sans antialiased h-screen flex overflow-hidden" x-data="{
    sidebarOpen: true,
    mobileMenuOpen: false,
    toggleSidebar() {
        if (window.innerWidth < 768) {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        } else {
            this.sidebarOpen = !this.sidebarOpen;
        }
    }
}">

    <!-- Mobile Drawer Navigation Sheet -->
    <div x-show="mobileMenuOpen" class="relative z-50 md:hidden" role="dialog" aria-modal="true" style="display: none;">

        <!-- Backdrop Overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
            class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"></div>

        <!-- Drawer Body -->
        <div class="fixed inset-y-0 left-0 flex max-w-full">
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="w-64 bg-sidebar flex flex-col h-full border-r border-sidebar-border relative shadow-2xl transition-transform">

                <div class="absolute top-3.5 right-3 z-50">
                    <button @click="mobileMenuOpen = false"
                        class="p-1 rounded-md text-sidebar-foreground hover:text-foreground hover:bg-sidebar-border transition-colors focus:outline-none">
                        <i data-lucide="x" height="16" width="16"></i>
                    </button>
                </div>

                <!-- Enforce expanded mode inside mobile drawer -->
                <div class="h-full flex flex-col" x-data="{ sidebarOpen: true }">
                    {!! $sidebarHtml !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Sidebar Wrapper -->
    <div class="hidden md:block shrink-0">
        {!! $sidebarHtml !!}
    </div>

    <!-- Main Content Area Wrapper -->
    <div class="flex-1 flex flex-col min-h-0 overflow-hidden transition-all duration-300">

        <!-- Header / Top navigation bar -->
        <header class="h-14 border-b border-sidebar-border flex items-center px-4 justify-between shrink-0 bg-sidebar">
            <div class="flex items-center gap-2">
                <!-- Sidebar Toggle Button -->
                <button @click="toggleSidebar()"
                    class="p-1.5 rounded-md text-sidebar-foreground hover:text-foreground hover:bg-accent transition-colors"
                    aria-label="Toggle Sidebar">
                    <i data-lucide="panel-left" height="16" width="16"></i>
                </button>

                <!-- Divider -->
                <div class="w-px h-4 bg-sidebar-border mx-2"></div>

                <!-- Breadcrumbs -->
                {{ $breadcrumbs ?? '' }}
            </div>

            <!-- Right-side actions -->
            <div class="flex items-center gap-3">
                <!-- Theme Toggle -->
                <button x-data="{
                    toggleTheme() {
                        const isDark = document.documentElement.classList.contains('dark');
                        if (isDark) {
                            document.documentElement.classList.remove('dark');
                            localStorage.theme = 'light';
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.theme = 'dark';
                        }
                    }
                }" @click="toggleTheme()"
                    class="p-1.5 text-muted-foreground hover:text-foreground rounded-md hover:bg-accent">
                    <i data-lucide="sun" class="h-4 w-4 hidden dark:block"></i>
                    <i data-lucide="moon" class="h-4 w-4 block dark:hidden"></i>
                </button>

                <!-- Notification Bell avec Dropdown -->
                <div class="relative" x-data="notificationDropdown()" x-init="init()">
                    <!-- Cloche -->
                    <button @click="toggle" @click.away="close"
                        class="relative p-1.5 rounded-md text-sidebar-foreground hover:text-foreground hover:bg-accent"
                        aria-label="Notifications">
                        <i data-lucide="bell" height="16" width="16"></i>
                        <!-- Badge -->
                        <span x-show="unreadCount > 0" x-text="unreadCount"
                            class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-primary-foreground bg-destructive rounded-full min-w-[18px] min-h-[18px]">
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div x-cloak x-show="open" @click.away="close"
                        class="absolute right-0 mt-2 w-80 md:w-96 bg-sidebar rounded-md shadow-lg overflow-hidden z-50 border border-sidebar-border"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                        <!-- Header -->
                        <div class="p-3 border-b border-sidebar-border flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-foreground">Notifications</h3>
                            <button @click="markAllAsRead"
                                class="text-xs text-primary hover:underline transition-colors">
                                Tout marquer comme lu
                            </button>
                        </div>

                        <!-- Liste des notifications -->
                        <div class="max-h-96 overflow-y-auto divide-y divide-sidebar-border p-2">
                            <template x-for="notif in notifications" :key="notif.id">
                                <a :href="notif.data.property_id ? `/properties/${notif.data.property_id}` : '#'"
                                    @click.prevent="markAsRead(notif.id, $event)"
                                    class="block px-4 py-3 hover:bg-sidebar-border transition-colors cursor-pointer rounded-2xl"
                                    :class="{ 'bg-primary/5': !notif.read_at }">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-1 min-w-0">
                                            <!-- Nom complet -->
                                            <p class="text-sm font-medium text-foreground"
                                                x-text="notif.data.full_name">
                                            </p>

                                            <!-- Type de demande -->
                                            <p class="text-xs text-muted-foreground mt-0.5">
                                                <span x-text="'Demande de visite'"></span>
                                                <span x-show="notif.data.property_category"
                                                    x-text="notif.data.property_category"></span>
                                            </p>

                                            <!-- Date relative -->
                                            <p class="text-xs text-muted-foreground mt-1 opacity-70"
                                                x-text="notif.created_at">
                                            </p>
                                        </div>
                                        <span x-show="!notif.read_at"
                                            class="inline-block w-2 h-2 bg-primary rounded-full shrink-0 mt-1.5">
                                        </span>
                                    </div>
                                </a>
                            </template>
                            <div x-show="notifications.length === 0"
                                class="p-6 text-center text-sm text-muted-foreground">
                                <i data-lucide="bell-off" class="h-8 w-8 mx-auto mb-2 opacity-50"></i>
                                <p>Aucune notification</p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="p-2 border-t border-sidebar-border text-center">
                            <a href="{{ route('notifications.all') }}"
                                class="text-sm text-primary hover:underline transition-colors">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Panel Content -->
        <main class="flex-1 overflow-y-auto min-h-0 p-3 sm:p-4 bg-background flex flex-col gap-4">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
