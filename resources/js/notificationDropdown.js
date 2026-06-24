document.addEventListener('alpine:init', () => {
    Alpine.data('notificationDropdown', () => ({
        open: false,
        notifications: [],
        unreadCount: 0,
        interval: null,
        isLoading: false,

        init() {
            this.fetchNotifications();
            // Polling toutes les 30 secondes
            this.interval = setInterval(() => {
                this.fetchNotifications(true);
            }, 30000);

            // Nettoyer l'intervalle lors de la destruction
            this.$watch('open', (value) => {
                if (value) {
                    this.fetchNotifications();
                }
            });
        },

        toggle() {
            this.open = !this.open;
        },

        close() {
            this.open = false;
        },

        async fetchNotifications(silent = false) {
            if (this.isLoading) return;
            
            try {
                this.isLoading = true;
                const response = await fetch('/notifications', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Erreur lors du chargement');
                
                const data = await response.json();
                this.notifications = data.notifications || [];
                this.unreadCount = data.unread_count || 0;
                
            } catch (error) {
                console.error('Erreur lors du chargement des notifications:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async markAsRead(id, event) {
            try {
                const response = await fetch(`/notifications/${id}/read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Erreur lors du marquage');
                
                const data = await response.json();
                
                // Mettre à jour localement
                const notif = this.notifications.find(n => n.id === id);
                if (notif) {
                    notif.read_at = new Date().toISOString();
                    this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                }

                // Rediriger vers la page du bien si l'utilisateur a cliqué sur le lien
                if (event && event.target.closest('a')) {
                    window.location.href = event.target.closest('a').href;
                }

            } catch (error) {
                console.error('Erreur lors du marquage comme lu:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/notifications/read-all', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Erreur lors du marquage');
                
                // Marquer toutes localement
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;

            } catch (error) {
                console.error('Erreur lors du marquage tout comme lu:', error);
            }
        },

        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        }
    }));
});