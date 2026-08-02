<x-layout.dashboard>

    <x-slot:title>
        Messagerie | Samaritain
    </x-slot:title>

    <x-slot:sidebar>
        <x-sidebar>
            <x-sidebar.header name="Samaritain" role="Messagerie" />

            <div class="p-3">
                <flux:button href="{{ route('messenger') }}" variant="primary" class="w-full">
                    <i data-lucide="message-circle" class="w-4 h-4 mr-2"></i>
                    Messagerie
                </flux:button>
            </div>

            @if (auth()->user()->profile_image)
                <x-sidebar.footer name="{{ auth()->user()->name }}" email="{{ auth()->user()->email }}"
                    avatar="{{ auth()->user()->profileUrl() }}" />
            @else
                <x-sidebar.footer name="{{ auth()->user()->name }}" email="{{ auth()->user()->email }}" />
            @endif
        </x-sidebar>
    </x-slot:sidebar>

    <x-slot:breadcrumbs>
        <x-breadcrumb />
    </x-slot:breadcrumbs>

    @if (session('success'))
        <div class="mx-3 mt-3 md:mx-auto p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400 text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mx-3 mt-3 md:mx-auto p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="h-[calc(100vh-120px)]">
        <div class="flex h-full bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg overflow-hidden shadow-sm">
            <div class="w-full md:w-80 border-r dark:border-gray-800 h-full">
                <livewire:messenger.conversation-list />
            </div>
            <div class="hidden md:block flex-1 h-full">
                <livewire:messenger.chat-window />
            </div>
        </div>
    </div>

    @stack('scripts')

</x-layout.dashboard>