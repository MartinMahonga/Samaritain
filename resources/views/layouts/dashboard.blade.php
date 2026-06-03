<x-layout.dashboard>
    
    <!-- Title Slot -->
    <x-slot:title>
        Dashboard - Samaritain Immobilier
    </x-slot:title>

    <!-- Sidebar Navigation Slot -->
    <x-slot:sidebar>
        <x-sidebar>
            <!-- Workspace / Organization Header -->
            <x-sidebar.header name="Samaritain Immobilier" plan="Professionnel" />
            
            <!-- Platform Group -->
            <x-sidebar.group label="Gestion">
                <!-- Collapsible Properties item, expanded by default -->
                <x-sidebar.item icon="home" label="Propriétés" :active="false" :expanded="true">
                    <x-sidebar.sub-item label="Tous les biens" href="#" />
                    <x-sidebar.sub-item label="Les biens vérifiés" href="#" />
                    <x-sidebar.sub-item label="Les biens non vérifiés" href="#" />
                    <x-sidebar.sub-item label="Les biens en cour de vérification" href="#" />
                    <x-sidebar.sub-item label="Nouvelles annonces" href="#" />
                </x-sidebar.item>
                
                <x-sidebar.item icon="drill" label="Artisans" href="#" />
                <x-sidebar.item icon="users" label="Clients" href="#" />
                <x-sidebar.item icon="ticket" label="Passe visite" href="#" />
                <x-sidebar.item icon="wallet" label="Transactions" href="#" />
                <x-sidebar.item icon="settings-2" label="Paramètres" href="#" />
            </x-sidebar.group>

            <!-- Projects Group -->
            <x-sidebar.group label="Segments">
                <x-sidebar.item icon="building" label="Résidentiel" href="#" />
                <x-sidebar.item icon="briefcase" label="Commercial" href="#" />
                <x-sidebar.item icon="map" label="Locations" href="#" />
            </x-sidebar.group>

            <!-- User Profile Footer -->
            @if(auth()->user()->avatar)
                <x-sidebar.footer name="{{ auth()->user()->name }}" email="{{ auth()->user()->email }}" avatar="{{ auth()->user()->avatar }}" />
            @else
                <x-sidebar.footer name="{{ auth()->user()->name }}" email="{{ auth()->user()->email }}" />
            @endif
        </x-sidebar>
    </x-slot:sidebar>

    <!-- Breadcrumb Slot -->
    <x-slot:breadcrumbs>
        <x-breadcrumb />
    </x-slot:breadcrumbs>

    @yield('content')
    
</x-layout.dashboard>
