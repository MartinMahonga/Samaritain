<x-layout.dashboard>

    <!-- Title Slot -->
    <x-slot:title>
        Dashboard - Samaritain Immobilier
    </x-slot:title>

    <!-- Sidebar Navigation Slot -->
    <x-slot:sidebar>
        <x-sidebar>
            <!-- Workspace / Organization Header -->
            <x-sidebar.header name="Samaritain Immobilier" role="Admin" />

            <!-- Platform Group -->
            <x-sidebar.group label="Gestion">
                <!-- Collapsible Properties item, expanded by default -->
                <x-sidebar.item icon="layout-dashboard" label="Tableau de bord" href="{{ route('admin.index') }}" :active="request()->routeIs('admin.index')" />
                <x-sidebar.item icon="home" label="Propriétés" :active="request()->routeIs('admin.property.*')" :expanded="request()->routeIs('admin.property.*')">
                    <x-sidebar.sub-item label="Tous les biens" href="{{ route('admin.property.index') }}"
                        :active="request()->routeIs('admin.property.index')" />

                    <x-sidebar.sub-item label="Les biens vérifiés" href="#" :active="request()->routeIs('admin.property.verified')" />

                    <x-sidebar.sub-item label="Les biens non vérifiés" href="#" :active="request()->routeIs('admin.property.unverified')" />

                    <x-sidebar.sub-item label="Les biens en cour de vérification" href="#" :active="request()->routeIs('admin.property.pending')" />

                    <x-sidebar.sub-item label="Nouvelles annonces" href="#" :active="request()->routeIs('admin.property.new')" />
                </x-sidebar.item>

                <x-sidebar.item icon="land-plot" label="Parcelles" href="{{ route('admin.parcelle.index') }}" :active="request()->routeIs('admin.parcelle.index')" />
                <x-sidebar.item icon="drill" label="Artisans" href="{{ route('admin.artisans.index') }}" :active="request()->routeIs('admin.artisans.*')">
                    <x-sidebar.sub-item label="Tous les artisans" href="{{ route('admin.artisans.index') }}"
                        :active="request()->routeIs('admin.artisans.index')" />

                    <x-sidebar.sub-item label="Les artisans en attente de vérification" href="{{ route('admin.artisans.pending') }}" :active="request()->routeIs('admin.artisans.pending')" />

                    <x-sidebar.sub-item label="Les artisans suspendus" href="#" :active="request()->routeIs('admin.property.pending')" />
                </x-sidebar.item>
                <x-sidebar.item icon="ticket" label="Passe visite" href="{{ route('passes.index') }}" :active="request()->routeIs('passes.index')" />
                <x-sidebar.item icon="inbox" label="Inbox" href="#" />
                <x-sidebar.item icon="settings-2" label="Paramètres" href="#" />
            </x-sidebar.group>

            <!-- Projects Group -->
            <x-sidebar.group label="Segments">
                @can('manage-members')
                    <x-sidebar.item icon="users" label="Membres" href="{{ route('admin.members.index') }}" />
                    <x-sidebar.item icon="briefcase" label="Invitations" href="{{ route('admin.invitations.index') }}" />
                @endcan
                @can('manage-roles')
                    <x-sidebar.item icon="map" label="Rôles & Permissions" href="{{ route('admin.roles.index') }}" />
                @endcan
            </x-sidebar.group>

            <!-- User Profile Footer -->
            @if (auth()->user()->profile_image)
                <x-sidebar.footer name="{{ auth()->user()->name }}" email="{{ auth()->user()->email }}"
                    avatar="{{ auth()->user()->profile_image }}" />
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
        <x-alert style="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @yield('content')

</x-layout.dashboard>