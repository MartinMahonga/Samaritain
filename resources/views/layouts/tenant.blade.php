<x-layout.dashboard>

    <x-slot:title>
        Espace Locataire | Samaritain
    </x-slot:title>

    <x-slot:sidebar>
        <x-sidebar>
            <x-sidebar.header name="Samaritain" role="Locataire" />

            <x-sidebar.group label="Mon Espace">
                <x-sidebar.item icon="layout-dashboard" label="Tableau de bord" href="{{ route('tenant.dashboard') }}" :active="request()->routeIs('tenant.dashboard')" />
                <x-sidebar.item icon="file-signature" label="Mon contrat" href="{{ route('tenant.contracts') }}" :active="request()->routeIs('tenant.contracts')" />
                <x-sidebar.item icon="banknote" label="Mes paiements" href="{{ route('tenant.payments') }}" :active="request()->routeIs('tenant.payments')" />
                <x-sidebar.item icon="wrench" label="Interventions" href="{{ route('tenant.interventions') }}" :active="request()->routeIs('tenant.interventions')" />
                <x-sidebar.item icon="folder-open" label="Mes documents" href="{{ route('tenant.documents') }}" :active="request()->routeIs('tenant.documents')" />

                <!-- Messagerie -->
                <x-sidebar.item icon="message-circle" label="Messagerie" href="{{ route('tenant.messenger') }}" :active="request()->routeIs('tenant.messenger')" />
            </x-sidebar.group>

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

    @yield('content')

    @stack('scripts')

</x-layout.dashboard>