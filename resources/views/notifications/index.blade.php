@extends('layouts.dashboard')

@section('content')
    <div x-data="notificationsApp()" x-init="init()" class="h-full flex flex-col">

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-4 px-1">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-semibold text-foreground">Notifications</h1>
                <span x-show="unreadCount > 0"
                    class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full bg-primary text-primary-foreground text-xs font-semibold"
                    x-text="unreadCount"></span>
            </div>
            <div class="flex items-center gap-3">
                <button @click="markAllAsRead()" x-show="unreadCount > 0"
                    class="inline-flex items-center gap-1.5 text-sm text-primary hover:underline transition">
                    <i data-lucide="check-check" class="w-4 h-4"></i>
                    Tout marquer comme lu
                </button>
            </div>
        </div>

        {{-- Layout 3 colonnes --}}
        <div class="flex flex-1 overflow-hidden gap-4 relative">

            {{-- Colonne 1 : Liste --}}
            <div x-show="!isMobile || !selectedId"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="w-full md:w-[350px] lg:w-[380px] flex-shrink-0 flex flex-col bg-card rounded-2xl shadow-sm border border-border overflow-hidden"
                :class="{ 'absolute inset-0 z-10': isMobile && !selectedId }">

                {{-- Barre de recherche et filtres --}}
                <div class="p-4 border-b border-border">
                    <div class="relative">
                        <i data-lucide="search"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"></i>
                        <input type="text" x-model="searchQuery" placeholder="Rechercher..."
                            class="w-full pl-9 pr-9 py-2 text-sm bg-background border border-input rounded-lg focus:ring-2 focus:ring-ring focus:border-transparent outline-none transition">
                        <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 p-0.5 rounded-full hover:bg-muted transition">
                            <i data-lucide="x" class="w-3.5 h-3.5 text-muted-foreground"></i>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <button @click="filter = 'all'"
                            :class="{ 'bg-primary/10 text-primary': filter === 'all', 'text-muted-foreground hover:bg-muted': filter !== 'all' }"
                            class="px-3 py-1 text-xs font-medium rounded-full transition">Toutes</button>
                        <button @click="filter = 'unread'"
                            :class="{ 'bg-primary/10 text-primary': filter === 'unread', 'text-muted-foreground hover:bg-muted': filter !== 'unread' }"
                            class="px-3 py-1 text-xs font-medium rounded-full transition">Non lues</button>
                        <button @click="filter = 'read'"
                            :class="{ 'bg-primary/10 text-primary': filter === 'read', 'text-muted-foreground hover:bg-muted': filter !== 'read' }"
                            class="px-3 py-1 text-xs font-medium rounded-full transition">Lues</button>
                    </div>
                </div>

                {{-- Liste des notifications --}}
                <div class="flex-1 overflow-y-auto divide-y divide-border">
                    <template x-for="group in groupedNotifications" :key="group.label">
                        <div>
                            <div class="sticky top-0 px-4 py-1.5 bg-card/95 backdrop-blur border-b border-border text-[11px] font-semibold text-muted-foreground uppercase tracking-wider z-[1]"
                                x-text="group.label"></div>
                            <template x-for="notif in group.items" :key="notif.id">
                                <div @click="selectNotification(notif)"
                                    class="px-4 py-3 cursor-pointer transition hover:bg-muted group"
                                    :class="{
                                        'bg-primary/5': selectedId === notif.id,
                                        'border-l-4 border-primary': !notif.read_at
                                    }">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0"
                                            :style="`background: ${avatarColor(notif)}`">
                                            <span x-text="getInitials(notif)"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <p class="text-sm font-medium text-foreground truncate"
                                                    x-text="getFullName(notif)"></p>
                                                <span class="text-xs text-muted-foreground shrink-0"
                                                    x-text="notif.created_at"></span>
                                            </div>
                                            <p class="text-xs text-muted-foreground truncate">
                                                <span x-text="'Demande de visite'"></span>
                                                <span x-show="notif.data.property_category"
                                                    x-text="' · ' + notif.data.property_category"></span>
                                            </p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span x-show="!notif.read_at"
                                                    class="inline-block w-1.5 h-1.5 bg-primary rounded-full"></span>
                                                <span x-show="!notif.read_at"
                                                    class="text-[10px] font-medium text-primary">Non lu</span>
                                                <span x-show="notif.read_at"
                                                    class="text-[10px] text-muted-foreground">Lu</span>
                                            </div>
                                        </div>
                                        <button @click.stop="confirmDelete(notif.id)"
                                            class="opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded-md hover:bg-destructive/10 text-muted-foreground hover:text-destructive"
                                            title="Supprimer">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Aucun résultat pour le filtre/recherche --}}
                    <div x-show="notifications.length > 0 && filteredNotifications.length === 0"
                        class="p-8 text-center text-muted-foreground">
                        <i data-lucide="search-x" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                        <p class="text-sm">Aucun résultat</p>
                        <p class="text-xs mt-1">Essayez un autre filtre ou une autre recherche</p>
                    </div>

                    {{-- Aucune notification du tout --}}
                    <div x-show="notifications.length === 0"
                        class="p-8 text-center text-muted-foreground">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                        <p class="text-sm">Aucune notification</p>
                        <p class="text-xs mt-1">Les nouvelles demandes apparaîtront ici</p>
                    </div>
                </div>

                {{-- Pagination --}}
                @if (isset($notifications) && method_exists($notifications, 'links'))
                    <div class="border-t border-border p-3">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>

            {{-- Colonne 2 : Détails --}}
            <div x-show="!isMobile || selectedId"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="flex-1 min-w-0 bg-card rounded-2xl shadow-sm border border-border overflow-hidden flex flex-col"
                :class="{ 'absolute inset-0 z-20': isMobile && selectedId }">
                <template x-if="selectedNotification">
                    <div class="flex flex-col h-full">
                        {{-- En-tête avec bouton retour sur mobile --}}
                        <div class="p-5 border-b border-border flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <button @click="backToList()" x-show="isMobile"
                                    class="md:hidden p-1 rounded-md hover:bg-muted transition">
                                    <i data-lucide="arrow-left" class="w-5 h-5 text-muted-foreground"></i>
                                </button>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold text-base flex-shrink-0"
                                        :style="`background: ${avatarColor(selectedNotification)}`">
                                        <span x-text="getInitials(selectedNotification)"></span>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-semibold text-foreground"
                                            x-text="getFullName(selectedNotification)"></h2>
                                        <p class="text-sm text-muted-foreground flex items-center gap-1.5">
                                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                            <span x-text="selectedNotification.data.phone || 'Non renseigné'"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span x-show="!selectedNotification.read_at"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">Nouveau</span>
                                <span x-show="selectedNotification.read_at"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-muted text-muted-foreground">Lu</span>
                                <button @click="confirmDelete(selectedNotification.id)"
                                    class="p-1.5 rounded-md hover:bg-destructive/10 text-muted-foreground hover:text-destructive transition"
                                    title="Supprimer cette notification">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Corps du détail --}}
                        <div class="flex-1 overflow-y-auto p-5 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-start gap-3 p-3 rounded-lg bg-muted/50">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-muted-foreground mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Ville</p>
                                        <p class="text-sm text-foreground" x-text="selectedNotification.data.city || 'Non renseignée'"></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 p-3 rounded-lg bg-muted/50">
                                    <i data-lucide="calendar-clock" class="w-4 h-4 text-muted-foreground mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Créneau préféré</p>
                                        <p class="text-sm text-foreground" x-text="selectedNotification.data.preferred_date || 'Non renseigné'"></p>
                                    </div>
                                </div>
                                <div class="sm:col-span-2 flex items-start gap-3 p-3 rounded-lg bg-muted/50">
                                    <i data-lucide="home" class="w-4 h-4 text-muted-foreground mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Bien concerné</p>
                                        <p class="text-sm text-foreground">
                                            <span x-text="selectedNotification.data.property_category || 'Non spécifié'"></span>
                                            <span x-show="selectedNotification.data.property_title"
                                                x-text="' · ' + selectedNotification.data.property_title"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div x-show="selectedNotification.data.message">
                                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                    <i data-lucide="message-square-quote" class="w-3.5 h-3.5"></i>
                                    Message
                                </p>
                                <div class="bg-muted rounded-xl p-4 border border-border">
                                    <p class="text-sm text-foreground italic"
                                        x-text="'\"' + selectedNotification.data.message + '\"'"></p>
                                </div>
                            </div>

                            <div class="text-sm text-foreground/90 leading-relaxed">
                                Le client souhaite visiter ce bien immobilier.
                                Il a indiqué une préférence pour une visite le
                                <span class="font-medium" x-text="selectedNotification.data.preferred_date || 'à convenir'"></span>.
                                Une prise de contact rapide est recommandée.
                            </div>

                            <div class="flex flex-wrap gap-3 pt-2 border-t border-border pt-4">
                                <button @click="markAsRead(selectedNotification.id)"
                                    x-show="!selectedNotification.read_at"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground text-sm font-medium rounded-lg transition shadow-sm">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    Marquer comme lu
                                </button>
                                <a :href="selectedNotification.data.property_id ? '{{ url('/properties') }}/' + selectedNotification.data.property_id : '#'"
                                    x-show="selectedNotification.data.property_id"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-secondary hover:bg-secondary/80 text-secondary-foreground text-sm font-medium rounded-lg transition shadow-sm">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                    Voir le bien
                                </a>
                                <button @click="confirmDelete(selectedNotification.id)"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-destructive/10 hover:bg-destructive/20 text-destructive text-sm font-medium rounded-lg transition shadow-sm">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <template x-if="!selectedNotification">
                    <div class="flex-1 flex items-center justify-center text-muted-foreground">
                        <div class="text-center">
                            <i data-lucide="mouse-pointer-click" class="w-10 h-10 mx-auto mb-3 opacity-30"></i>
                            <p class="text-base font-medium">Sélectionnez une notification</p>
                            <p class="text-sm">Choisissez un élément dans la liste de gauche</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Modale de confirmation de suppression --}}
        <div x-show="deleteTargetId !== null" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40"
            x-transition.opacity>
            <div @click.outside="deleteTargetId = null"
                class="bg-card rounded-2xl shadow-lg border border-border max-w-sm w-full p-6">
                <div class="flex items-center justify-center w-11 h-11 rounded-full bg-destructive/10 mb-4">
                    <i data-lucide="trash-2" class="w-5 h-5 text-destructive"></i>
                </div>
                <h3 class="text-base font-semibold text-foreground mb-1">Supprimer cette notification ?</h3>
                <p class="text-sm text-muted-foreground mb-5">Cette action est irréversible.</p>
                <div class="flex justify-end gap-3">
                    <button @click="deleteTargetId = null"
                        class="px-4 py-2 text-sm font-medium text-foreground rounded-lg hover:bg-muted transition">
                        Annuler
                    </button>
                    <button @click="deleteNotification(deleteTargetId)"
                        class="px-4 py-2 text-sm font-medium bg-destructive text-white rounded-lg hover:bg-destructive/90 transition">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Alpine --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationsApp', () => ({
                notifications: @json(isset($notifications) ? $notifications->items() : []),
                selectedId: null,
                filter: 'all',
                searchQuery: '',
                unreadCount: 0,
                notificationCount: @json(isset($notifications) ? $notifications->total() : 0),
                isMobile: window.innerWidth < 768,
                deleteTargetId: null,

                get filteredNotifications() {
                    let items = this.notifications;
                    if (this.filter === 'unread') {
                        items = items.filter(n => !n.read_at);
                    } else if (this.filter === 'read') {
                        items = items.filter(n => n.read_at);
                    }
                    if (this.searchQuery.trim() !== '') {
                        const q = this.searchQuery.toLowerCase();
                        items = items.filter(n => {
                            const fullName = this.getFullName(n).toLowerCase();
                            const phone = n.data.phone || '';
                            const city = n.data.city || '';
                            return fullName.includes(q) || phone.includes(q) || city.includes(q);
                        });
                    }
                    return items;
                },

                get groupedNotifications() {
                    const groups = { "Aujourd'hui": [], "Cette semaine": [], "Plus ancien": [] };
                    const now = new Date();
                    const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                    const weekAgo = new Date(startOfToday);
                    weekAgo.setDate(weekAgo.getDate() - 7);

                    this.filteredNotifications.forEach(n => {
                        const date = new Date(n.created_at_raw || n.created_at);
                        if (!isNaN(date) && date >= startOfToday) {
                            groups["Aujourd'hui"].push(n);
                        } else if (!isNaN(date) && date >= weekAgo) {
                            groups["Cette semaine"].push(n);
                        } else {
                            groups["Plus ancien"].push(n);
                        }
                    });

                    return Object.entries(groups)
                        .filter(([, items]) => items.length > 0)
                        .map(([label, items]) => ({ label, items }));
                },

                get selectedNotification() {
                    return this.notifications.find(n => n.id === this.selectedId) || null;
                },

                init() {
                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 768;
                    });
                    this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                },

                selectNotification(notif) {
                    this.selectedId = notif.id;
                },

                backToList() {
                    this.selectedId = null;
                },

                getFullName(notif) {
                    return notif.data.full_name || (notif.data.first_name + ' ' + notif.data.last_name) || 'Client';
                },

                getInitials(notif) {
                    const name = this.getFullName(notif);
                    const parts = name.split(' ');
                    if (parts.length >= 2) {
                        return parts[0][0] + parts[1][0];
                    }
                    return name.substring(0, 2).toUpperCase();
                },

                avatarColor(notif) {
                    const palette = [
                        'linear-gradient(135deg,#6366f1,#8b5cf6)',
                        'linear-gradient(135deg,#0ea5e9,#22d3ee)',
                        'linear-gradient(135deg,#f59e0b,#f97316)',
                        'linear-gradient(135deg,#10b981,#34d399)',
                        'linear-gradient(135deg,#ec4899,#f43f5e)',
                        'linear-gradient(135deg,#8b5cf6,#a855f7)',
                    ];
                    const name = this.getFullName(notif);
                    let hash = 0;
                    for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
                    return palette[Math.abs(hash) % palette.length];
                },

                confirmDelete(id) {
                    this.deleteTargetId = id;
                },

                async markAsRead(id) {
                    try {
                        const response = await fetch(`{{ url('/notifications') }}/${id}/read`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        if (response.ok) {
                            const notif = this.notifications.find(n => n.id === id);
                            if (notif) {
                                notif.read_at = new Date().toISOString();
                                this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                            }
                        }
                    } catch (error) {
                        console.error('Erreur lors du marquage :', error);
                    }
                },

                async markAllAsRead() {
                    try {
                        const response = await fetch(`{{ route('notifications.mark-all-read') }}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        if (response.ok) {
                            this.notifications.forEach(n => n.read_at = new Date().toISOString());
                            this.unreadCount = 0;
                        }
                    } catch (error) {
                        console.error('Erreur lors du marquage tout :', error);
                    }
                },

                async deleteNotification(id) {
                    this.deleteTargetId = null;
                    try {
                        const response = await fetch(`{{ url('/notifications') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const index = this.notifications.findIndex(n => n.id === id);
                            if (index !== -1) {
                                this.notifications.splice(index, 1);
                                if (this.selectedId === id) {
                                    this.selectedId = this.notifications.length > 0 ? this.notifications[0].id : null;
                                }
                                this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                                this.notificationCount = this.notificationCount - 1;
                            }
                        }
                    } catch (error) {
                        console.error('Erreur lors de la suppression :', error);
                    }
                }
            }));
        });
    </script>
@endsection