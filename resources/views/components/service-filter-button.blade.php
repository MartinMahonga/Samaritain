@props([
    'categories' => [],
    'selected' => 'all',
    'onSelect' => null,
])

@php
    // Si les catégories sont vides, on utilise un tableau vide
    if (empty($categories)) {
        $categories = collect([
            (object) ['id' => null, 'name' => 'Tous', 'slug' => 'all'],
        ]);
    }
    
    // Formater les catégories pour Alpine.js
    $formattedCategories = $categories->map(fn($cat) => [
        'id' => $cat->id ?? null,
        'name' => $cat->name ?? 'Tous',
        'slug' => $cat->slug ?? 'all'
    ])->values();
@endphp

<div 
    x-data="serviceFilter({
        categories: {{ json_encode($formattedCategories) }},
        selected: '{{ $selected }}',
        onSelect: {{ $onSelect ? 'true' : 'false' }}
    })"
    x-on:select-category.window="select($event.detail)"
    class="relative w-full"
    x-init="initFromUrl()"
>
    <!-- Barre de filtres avec scroll horizontal -->
    <div class="relative overflow-x-auto scrollbar-hide">
        <div 
            class="flex gap-2 md:gap-3 py-3 px-1 min-w-max"
            x-ref="scrollContainer"
        >
            <template x-for="cat in categories" :key="cat.id">
                <x-service-filter-button 
                    :name="cat.name"
                    :icon="getIconForCategory(cat.slug)"
                    :active="selected === cat.slug"
                    x-on:click="select(cat.slug)"
                    x-bind:aria-current="selected === cat.slug ? 'page' : false"
                />
            </template>
        </div>
    </div>
    
    <!-- Indicateur de défilement -->
    <div 
        x-show="$refs.scrollContainer.scrollWidth > $refs.scrollContainer.clientWidth"
        class="absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-background to-transparent pointer-events-none"
        x-cloak
    ></div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('serviceFilter', (config) => ({
            categories: config.categories || [],
            selected: config.selected || 'all',
            onSelect: config.onSelect || false,
            
            select(categorySlug) {
                this.selected = categorySlug;
                
                // Animation de changement
                this.$nextTick(() => {
                    const buttons = this.$root.querySelectorAll('button');
                    buttons.forEach(btn => {
                        if (btn.dataset.active === 'true') {
                            btn.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                btn.style.transform = '';
                            }, 150);
                        }
                    });
                });
                
                // Mettre à jour l'URL
                this.updateUrl(categorySlug);
                
                // Émettre l'événement pour la mise à jour des artisans
                this.$dispatch('category-changed', { category: categorySlug });
                
                // Callback externe si fourni
                if (this.onSelect) {
                    this.$dispatch('filter-selected', { category: categorySlug });
                }
            },
            
            updateUrl(categorySlug) {
                const url = new URL(window.location.href);
                if (categorySlug === 'all') {
                    url.searchParams.delete('category');
                } else {
                    url.searchParams.set('category', categorySlug);
                }
                window.history.pushState({ category: categorySlug }, '', url.toString());
            },
            
            initFromUrl() {
                const params = new URLSearchParams(window.location.search);
                const categoryFromUrl = params.get('category');
                if (categoryFromUrl && this.categories.some(c => c.slug === categoryFromUrl)) {
                    this.selected = categoryFromUrl;
                }
            },
            
            getIconForCategory(slug) {
                const icons = {
                    'all': 'squares-2x2',
                    'maçon': 'building-office',
                    'plombier': 'wrench',
                    'électricien': 'bolt',
                    'peintre': 'paint-brush',
                    'menuisier': 'hammer',
                    'climatisation': 'fan',
                    'toiture': 'home-roof',
                    'jardinage': 'flower',
                    'nettoyage': 'sparkles',
                    'architecte': 'building-office',
                    'carreleur': 'squares-2x2',
                    'serrurier': 'lock',
                    'vitrier': 'globe-alt',
                    'électricité': 'bolt',
                    'plomberie': 'wrench',
                    'chauffage': 'fire',
                    'isolation': 'shield-check',
                };
                return icons[slug] || 'squares-2x2';
            }
        }));
    });
</script>
@endpush