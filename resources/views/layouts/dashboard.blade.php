<x-layout.dashboard>
    
    <!-- Title Slot -->
    <x-slot:title>
        Dashboard - Shadcn Sidebar-07
    </x-slot:title>

    <!-- Sidebar Navigation Slot -->
    <x-slot:sidebar>
        <x-sidebar>
            <!-- Workspace / Organization Header -->
            <x-sidebar.header name="Acme Inc" plan="Enterprise" />
            
            <!-- Platform Group -->
            <x-sidebar.group label="Platform">
                <!-- Collapsible Playground item, expanded by default -->
                <x-sidebar.item icon="terminal" label="Playground" :active="false" :expanded="true">
                    <x-sidebar.sub-item label="History" href="#" />
                    <x-sidebar.sub-item label="Starred" href="#" />
                    <x-sidebar.sub-item label="Settings" href="#" />
                </x-sidebar.item>
                
                <x-sidebar.item icon="bot" label="Models" href="#" />
                <x-sidebar.item icon="book-open" label="Documentation" href="#" />
                <x-sidebar.item icon="settings-2" label="Settings" href="#" />
            </x-sidebar.group>

            <!-- Projects Group -->
            <x-sidebar.group label="Projects">
                <x-sidebar.item icon="frame" label="Design Engineering" href="#" />
                <x-sidebar.item icon="pie-chart" label="Sales & Marketing" href="#" />
                <x-sidebar.item icon="map" label="Travel" href="#" />
                <x-sidebar.item icon="more-horizontal" label="More" href="#" />
            </x-sidebar.group>

            <!-- User Profile Footer -->
            <x-sidebar.footer name="shadcn" email="m@example.com" avatar="https://github.com/shadcn.png" />
        </x-sidebar>
    </x-slot:sidebar>

    <!-- Breadcrumb Slot -->
    <x-slot:breadcrumbs>
        <x-breadcrumb />
    </x-slot:breadcrumbs>

    @yield('content')
    
</x-layout.dashboard>
