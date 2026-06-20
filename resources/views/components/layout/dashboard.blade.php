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
            background: rgba(63, 63, 70, 0.4);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(63, 63, 70, 0.7);
        }
    </style>
</head>
@php
    $sidebarHtml = (string) $sidebar;
@endphp

<body class="bg-[var(--background)] text-[var(--foreground)] font-sans antialiased h-screen flex overflow-hidden"
    x-data="{
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
                class="w-64 bg-[var(--sidebar)] flex flex-col h-full border-r border-[var(--sidebar-border)] relative shadow-2xl transition-transform">

                <div class="absolute top-3.5 right-3 z-50">
                    <button @click="mobileMenuOpen = false"
                        class="p-1 rounded-md text-[var(--sidebar-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)] transition-colors focus:outline-none">
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
        <header
            class="h-14 border-b border-[var(--sidebar-border)] flex items-center px-4 justify-between shrink-0 bg-[var(--sidebar)]">
            <div class="flex items-center gap-2">
                <!-- Sidebar Toggle Button -->
                <button @click="toggleSidebar()"
                    class="p-1.5 rounded-md text-[var(--sidebar-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)] transition-colors focus:outline-none"
                    aria-label="Toggle Sidebar">
                    <i data-lucide="panel-left" height="16" width="16"></i>
                </button>

                <!-- Divider -->
                <div class="w-px h-4 bg-[var(--sidebar-border)] mx-2"></div>

                <!-- Breadcrumbs -->
                {{ $breadcrumbs ?? '' }}
            </div>

            <!-- Right-side actions -->
            <div class="flex items-center gap-3">
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
                    class="p-1.5 text-[var(--muted-foreground)] hover:text-[var(--foreground)] rounded-md hover:bg-[var(--sidebar-border)] transition-colors focus:outline-none">
                    <i data-lucide="sun" class="h-4 w-4 hidden dark:block"></i>
                    <i data-lucide="moon" class="h-4 w-4 block dark:hidden"></i>
                </button>
                <button
                    class="p-1.5 rounded-md text-[var(--sidebar-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--sidebar-border)] transition-colors">
                    <i data-lucide="bell" height="16" width="16"></i>
                </button>
            </div>
        </header>

        <!-- Main Panel Content -->
        <main class="flex-1 overflow-y-auto min-h-0 p-3 sm:p-4 bg-[var(--background)] flex flex-col gap-4">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
