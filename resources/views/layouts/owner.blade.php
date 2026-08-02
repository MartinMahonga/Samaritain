<x-layout.dashboard>

    <!-- Title Slot -->
    <x-slot:title>
        Espace Propriétaire | Samaritain
    </x-slot:title>

    <!-- Sidebar Navigation Slot -->
    <x-slot:sidebar>
        <x-sidebar>
            <!-- Workspace / Organization Header -->
            <x-sidebar.header name="Samaritain" role="Propriétaire" />

            <!-- Platform Group -->
            <x-sidebar.group label="Espace Propriétaire">
                <!-- Portal Dashboard Overview -->
                <x-sidebar.item icon="layout-dashboard" label="Tableau de bord" href="{{ route('owner.dashboard') }}" :active="request()->routeIs('owner.dashboard')" />
                
                <!-- Financial Stats -->
                <x-sidebar.item icon="wallet" label="Finances & Stats" href="{{ route('owner.financial') }}" :active="request()->routeIs('owner.financial')" />

                <!-- Contracts -->
                <x-sidebar.item icon="file-signature" label="Contrats de bail" href="{{ route('owner.contracts.index') }}" :active="request()->routeIs('owner.contracts.*')" />

                <!-- Invoices -->
                <x-sidebar.item icon="receipt" label="Factures & Charges" href="{{ route('owner.invoices.index') }}" :active="request()->routeIs('owner.invoices.*')" />

                <!-- Interventions / Maintenance -->
                <x-sidebar.item icon="wrench" label="Maintenance & Travaux" href="{{ route('owner.interventions.index') }}" :active="request()->routeIs('owner.interventions.*')" />

                <!-- Inspections -->
                <x-sidebar.item icon="clipboard-check" label="États des lieux" href="{{ route('owner.inspections.index') }}" :active="request()->routeIs('owner.inspections.*')" />

                <!-- Documents -->
                <x-sidebar.item icon="folder-open" label="Documents" href="{{ route('owner.documents.index') }}" :active="request()->routeIs('owner.documents.*')" />

                <!-- Messagerie -->
                <x-sidebar.item icon="message-circle" label="Messagerie" href="{{ route('owner.messenger') }}" :active="request()->routeIs('owner.messenger')" />
            </x-sidebar.group>

            <!-- Links to standard listings -->
            <x-sidebar.group label="Mes annonces">
                <x-sidebar.item icon="home" label="Mes Propriétés" href="{{ route('property.dashboard') }}" />
                <x-sidebar.item icon="land-plot" label="Mes Parcelles" href="{{ route('parcelles.dashboard') }}" />
            </x-sidebar.group>

            <!-- User Profile Footer -->
            @if (auth()->user()->profile_image)
                <x-sidebar.footer name="{{ auth()->user()->name }}" email="{{ auth()->user()->email }}"
                    avatar="{{ auth()->user()->profileUrl() }}" />
            @else
                <x-sidebar.footer name="{{ auth()->user()->name }}" email="{{ auth()->user()->email }}" />
            @endif
        </x-sidebar>
    </x-slot:sidebar>

    <!-- Breadcrumb Slot -->
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