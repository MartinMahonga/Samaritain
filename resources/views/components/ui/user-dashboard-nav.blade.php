<section class="border-b border-border">
    <x-blade-components::layout.container>
        <!-- Conteneur avec overflow-x-auto et scrollbar cachée -->
        <div class="overflow-x-auto scrollbar-hide">
            <nav class="flex gap-6 min-w-max">
                @php
                    $tabs = [
                        [
                            'route' => 'property.dashboard',
                            'icon' => 'warehouse',
                            'label' => 'Mes biens',
                            'show' => true,
                        ],
                        [
                            'route' => 'parcelles.dashboard',
                            'icon' => 'land-plot',
                            'label' => 'Mes parcelles',
                            'show' => true,
                        ],
                        [
                            'route' => 'artisan.dashboard',
                            'icon' => 'drill',
                            'label' => 'Artisan',
                            'show' => auth()->user()?->artisan,
                        ],
                        [
                            'route' => 'profile.show',
                            'icon' => 'settings',
                            'label' => 'Paramètres',
                            'show' => true,
                        ],
                    ];
                @endphp

                @foreach ($tabs as $tab)
                    @continue(!$tab['show'])
                    @php $active = $tab['route'] && request()->routeIs($tab['route']); @endphp

                    <a href="{{ $tab['route'] ? route($tab['route']) : $tab['href'] ?? '#' }}" @class([
                        'group flex items-center gap-2 px-1 py-3 text-sm whitespace-nowrap border-b-2 -mb-px transition-colors',
                        'border-primary text-primary font-semibold' => $active,
                        'border-transparent text-muted-foreground hover:text-foreground hover:border-border' => !$active,
                    ])>
                        <i data-lucide="{{ $tab['icon'] }}" @class([
                            'w-4 h-4 transition-colors',
                            'text-primary' => $active,
                            'text-muted-foreground group-hover:text-foreground' => !$active,
                        ])></i>
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </x-blade-components::layout.container>
</section>