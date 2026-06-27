id: 1, <section class="max-w-7xl mx-auto px-6 py-16" x-data="cityDistricts()" x-init="init()">
    
    <!-- En-tête avec switch de ville intégré -->
    <div class="text-center mb-10">
        <div class="flex items-center justify-center gap-2 mb-2">
            <!-- Switch intégré dans le label -->
            <button 
                @click="switchCity('brazzaville')"
                class="text-sm font-semibold font-display uppercase tracking-widest transition-all duration-300"
                :class="city === 'brazzaville' 
                    ? 'text-primary' 
                    : 'text-foreground hover:text-primary'"
            >
                Brazzaville
            </button>
            
            <span class="text-muted-foreground text-sm">/</span>
            
            <button 
                @click="switchCity('pointeNoire')"
                class="text-sm font-semibold font-display uppercase tracking-widest transition-all duration-300"
                :class="city === 'pointeNoire' 
                    ? 'text-primary' 
                    : 'text-foreground hover:text-primary'"
            >
                Pointe-Noire
            </button>
        </div>
        
        <h2 class="text-2xl md:text-3xl font-bold font-display text-foreground">Quartiers populaires</h2>
        <p class="text-muted-foreground mt-2 text-sm max-w-md mx-auto">
            Explorez les zones les plus recherchées de la ville.
        </p>
    </div>

    <!-- Grille des arrondissements -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        
        <template x-for="arrondissement in filteredDistricts" :key="arrondissement.name">
            <a 
                :href="`{{ route('property.index') }}?arrondissement_id=${encodeURIComponent(arrondissement.id)}`"
                class="group flex flex-col gap-3 p-5 rounded-2xl border border-accent hover:border-primary/30 hover:shadow-sm transition-all duration-200"
            >
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                    <span x-html="getIcon(arrondissement.icon)" class="w-5 h-5 text-primary"></span>
                </div>

                <div>
                    <p class="font-semibold text-foreground text-sm leading-tight" x-text="arrondissement.name"></p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        <span x-text="arrondissement.count"></span> 
                        bien<span x-show="arrondissement.count > 1">s</span> 
                        disponible<span x-show="arrondissement.count > 1">s</span>
                    </p>
                </div>

                <div class="flex items-center gap-1 text-xs text-primary font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    Voir les biens
                    <span x-html="getIcon('arrow-right')" class="w-3 h-3"></span>
                </div>
            </a>
        </template>
        
        <!-- Message si aucun arrondissement -->
        <div x-show="filteredDistricts.length === 0" class="col-span-full text-center py-8 text-foreground">
            Aucun arrondissement disponible pour cette ville.
        </div>
        
    </div>

</section>

<!-- Alpine.js Component -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cityDistricts', () => ({
            city: 'brazzaville',
            districts: {
                brazzaville: [
                    { id: 1, name: 'Bacongo', count: 14, icon: 'building-2' },
                    { id: 2, name: 'Moungali', count: 9, icon: 'building-2' },
                    { id: 3, name: 'Poto-Poto', count: 11, icon: 'store' },
                    { id: 4, name: 'Ouenzé', count: 7, icon: 'home' },
                    { id: 5, name: 'Talangaï', count: 6, icon: 'home' },
                    { id: 6, name: 'Makélékélé', count: 8, icon: 'building' },
                    { id: 7, name: 'Mfilou', count: 5, icon: 'landmark' },
                    { id: 8, name: 'Djiri', count: 4, icon: 'trees' }
                ],
                pointeNoire: [
                    { id: 1, name: 'Loandjili', count: 10, icon: 'building-2' },
                    { id: 2, name: 'Tié-Tié', count: 12, icon: 'store' },
                    { id: 3, name: 'Mvoumvou', count: 8, icon: 'home' },
                    { id: 4, name: 'Lumumba', count: 6, icon: 'building' },
                    { id: 5, name: 'Ngoyo', count: 5, icon: 'home' },
                    { id: 6, name: 'Mongo Mpoukou', count: 4, icon: 'trees' }
                ]
            },
            get filteredDistricts() {
                return this.districts[this.city] || [];
            },
            getIcon(iconName) {
                const icons = {
                    'building-2': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12h4"/><path d="M6 16h4"/><path d="M6 8h4"/><path d="M14 8h4"/><path d="M14 12h4"/><path d="M14 16h4"/></svg>`,
                    'store': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a3 3 0 0 0-3-3v0a3 3 0 0 0-3 3v4"/><path d="M2 7h20"/><path d="M2 7v1a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0V7"/></svg>`,
                    'home': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>`,
                    'building': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/><path d="M8 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M16 18h.01"/></svg>`,
                    'landmark': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary"><polygon points="3 22 3 10 12 3 21 10 21 22"/><path d="M3 10h18"/><path d="M8 22v-6"/><path d="M12 22V8"/><path d="M16 22v-6"/></svg>`,
                    'trees': `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary"><path d="M10 10v.2A3 3 0 0 1 8.9 16v0H5v0h3v0A3 3 0 0 1 10 10v.2Z"/><path d="M14 6v.2A3 3 0 0 1 12.9 12v0H9v0h3v0A3 3 0 0 1 14 6v.2Z"/><path d="M18 14v.2A3 3 0 0 1 16.9 20v0H13v0h3v0A3 3 0 0 1 18 14v.2Z"/><path d="M6 20h12"/></svg>`,
                    'arrow-right': `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>`
                };
                return icons[iconName] || icons['home'];
            },
            switchCity(city) {
                this.city = city;
            },
            init() {
                console.log('City districts component initialized');
            }
        }));
    });
</script>