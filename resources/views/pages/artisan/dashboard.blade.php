<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-primary text-white overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">Dashboard Artisan</h1>
                        <p class="text-white/90 mt-1">Gérez votre profil et suivez votre activité</p>
                    </div>
                </div>
                <x-btn href="{{ route('artisan.edit', $artisan) }}" style="secondary">
                    <x-slot:prefix>
                        <i data-lucide="user"></i>
                    </x-slot:prefix>
                    Modifier mon profil
                </x-btn>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statut vérification amélioré -->
        @if (!$artisan->verified)
            <div class="bg-amber-50 border-l-4 border-amber-500 rounded-xl p-4 mb-8">
                <div class="flex items-start gap-3">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-amber-800 text-sm font-medium">Profil en attente de validation</p>
                        <p class="text-amber-700 text-sm mt-1">
                            Votre profil est en cours de vérification par l'administration. 
                            Vous pourrez apparaître dans la marketplace une fois validé.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistiques améliorées -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-amber-400 to-amber-500 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="star" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</p>
                        <p class="text-xs text-gray-500">Note moyenne</p>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="flex text-amber-400 gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                            <i data-lucide="star" class="w-3 h-3 {{ $i <= round($stats['average_rating']) ? 'fill-current' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-emerald-400 to-emerald-500 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['reviews_count'] }}</p>
                        <p class="text-xs text-gray-500">Avis reçus</p>
                    </div>
                </div>
            </div>

            <div class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-500 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="images" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['projects_count'] }}</p>
                        <p class="text-xs text-gray-500">Réalisations</p>
                    </div>
                </div>
                @if($stats['projects_count'] > 0)
                    <p class="text-xs text-gray-400 mt-2">+{{ $stats['projects_count'] }} projet(s)</p>
                @endif
            </div>

            <div class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-orange-400 to-orange-500 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="mail" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['contacts_count'] }}</p>
                        <p class="text-xs text-gray-500">Demandes reçues</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides améliorées -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('artisan.projects.index', $artisan) }}"
                class="group bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-500 p-4 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="folder-plus" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Mes réalisations</h3>
                        <p class="text-sm text-gray-500 mt-1">Ajoutez et gérez vos photos de chantiers</p>
                    </div>
                    <i data-lucide="arrow-right" class="w-5 h-5 text-gray-400 ml-auto group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

            <a href="{{ route('artisans.show', $artisan) }}" target="_blank"
                class="group bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-500 p-4 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="eye" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Voir mon profil</h3>
                        <p class="text-sm text-gray-500 mt-1">Aperçu de votre profil public</p>
                    </div>
                    <i data-lucide="external-link" class="w-5 h-5 text-gray-400 ml-auto group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>

            <a href="{{ route('artisan.edit', $artisan) }}"
                class="group bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-green-400 to-green-500 p-4 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="settings" class="w-8 h-8 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Paramètres</h3>
                        <p class="text-sm text-gray-500 mt-1">Modifiez vos informations professionnelles</p>
                    </div>
                    <i data-lucide="arrow-right" class="w-5 h-5 text-gray-400 ml-auto group-hover:text-primary group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>
        </div>

        <!-- Derniers avis et contacts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Derniers avis -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="star" class="w-5 h-5 text-amber-500"></i>
                            <h3 class="font-semibold text-gray-900">Derniers avis</h3>
                        </div>
                        <span class="text-xs text-gray-400">{{ $stats['reviews_count'] }} au total</span>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @if ($recentReviews->isNotEmpty())
                        @foreach ($recentReviews as $review)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-sm font-medium">{{ substr($review->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-medium text-gray-900">{{ $review->user->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i data-lucide="star" class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <i data-lucide="message-square" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-500">Aucun avis pour le moment</p>
                            <p class="text-xs text-gray-400 mt-1">Les avis apparaîtront ici une fois reçus</p>
                        </div>
                    @endif
                </div>
                @if($recentReviews->isNotEmpty() && $stats['reviews_count'] > 3)
                    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-center">
                        <a href="#" class="text-xs text-primary hover:text-primary/80">Voir tous les avis →</a>
                    </div>
                @endif
            </div>

            <!-- Dernières demandes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-5 h-5 text-blue-500"></i>
                            <h3 class="font-semibold text-gray-900">Dernières demandes</h3>
                        </div>
                        <span class="text-xs text-gray-400">{{ $stats['contacts_count'] }} au total</span>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @if ($recentContacts->isNotEmpty())
                        @foreach ($recentContacts as $contact)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white text-sm font-medium">{{ substr($contact->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-medium text-gray-900">{{ $contact->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $contact->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <a href="tel:{{ $contact->phone }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                                                <i data-lucide="phone" class="w-3 h-3"></i>
                                                {{ $contact->phone }}
                                            </a>
                                            <a href="mailto:{{ $contact->email }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                                                <i data-lucide="mail" class="w-3 h-3"></i>
                                                {{ $contact->email }}
                                            </a>
                                        </div>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $contact->message }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-12">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-500">Aucune demande pour le moment</p>
                            <p class="text-xs text-gray-400 mt-1">Les clients peuvent vous contacter depuis votre profil</p>
                        </div>
                    @endif
                </div>
                @if($recentContacts->isNotEmpty() && $stats['contacts_count'] > 3)
                    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-center">
                        <a href="#" class="text-xs text-primary hover:text-primary/80">Voir toutes les demandes →</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Conseils et progression -->
        {{-- <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">Boostez votre visibilité</h3>
                    <p class="text-sm text-gray-600 mb-3">
                        Ajoutez des photos de qualité, répondez aux avis clients et complétez votre profil 
                        pour augmenter vos chances d'être contacté.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @if($stats['projects_count'] < 3)
                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full">📷 Ajoutez des réalisations</span>
                        @endif
                        @if(empty($artisan->bio))
                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full">✏️ Complétez votre description</span>
                        @endif
                        @if($stats['reviews_count'] < 5)
                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full">⭐ Encouragez vos clients à laisser des avis</span>
                        @endif
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>