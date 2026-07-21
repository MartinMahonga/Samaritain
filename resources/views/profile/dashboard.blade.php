<x-app-layout title="Mon tableau de bord">
    <div class="bg-primary text-white overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold">Mon tableau de bord</h1>
                        <p class="text-white/90 mt-1">Bienvenue, {{ $user->name }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 bg-white text-primary px-6 py-2 rounded-xl font-medium">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    Modifier mon profil
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques biens -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 p-3 rounded-xl">
                        <i data-lucide="home" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $propertiesStats['total'] }}</p>
                        <p class="text-xs text-gray-500">Total biens</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-green-500 p-3 rounded-xl">
                        <i data-lucide="eye" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $propertiesStats['active'] }}</p>
                        <p class="text-xs text-gray-500">Biens actifs</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-yellow-500 p-3 rounded-xl">
                        <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $propertiesStats['pending'] }}</p>
                        <p class="text-xs text-gray-500">En attente</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-red-500 p-3 rounded-xl">
                        <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $propertiesStats['sold'] }}</p>
                        <p class="text-xs text-gray-500">Vendus/Loués</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Derniers biens -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="home" class="w-5 h-5 text-blue-500"></i>
                            <h3 class="font-semibold text-gray-900">Mes derniers biens</h3>
                        </div>
                        <a href="{{ route('user.properties.index') }}" class="text-xs text-primary hover:text-primary/80">Voir tout →</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentProperties as $property)
                        <div class="p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $property->title }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ number_format($property->price, 0, ',', ' ') }} FCFA</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        @if($property->is_verify)
                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">✓ Vérifié</span>
                                        @else
                                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded">⏳ En attente</span>
                                        @endif
                                        @if($property->is_active)
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">Actif</span>
                                        @else
                                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">Inactif</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('property.show', $property) }}" class="text-gray-400 hover:text-blue-500">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('property.edit', $property) }}" class="text-gray-400 hover:text-yellow-500">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <i data-lucide="home" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-500">Aucun bien pour le moment</p>
                            <a href="{{ route('property.create') }}" class="inline-block mt-2 text-primary text-sm hover:underline">Ajouter un bien →</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Profil artisan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="briefcase" class="w-5 h-5 text-purple-500"></i>
                            <h3 class="font-semibold text-gray-900">Mon activité artisanale</h3>
                        </div>
                        @if($artisan)
                            <a href="{{ route('user.artisan.dashboard') }}" class="text-xs text-primary hover:text-primary/80">Gérer →</a>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($artisan)
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center">
                                @if($artisan->avatar)
                                    <img src="{{ Storage::url($artisan->avatar) }}" alt="{{ $artisan->business_name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <span class="text-white text-xl font-bold">{{ substr($artisan->business_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $artisan->business_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $artisan->profession }} • {{ $artisan->city }}</p>
                                @if($artisan->verified)
                                    <span class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded mt-1">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Vérifié
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded mt-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i> En attente
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-3 text-center border-t border-gray-100 pt-4">
                            <div>
                                <p class="text-xl font-bold text-gray-900">{{ $artisanStats['reviews_count'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Avis</p>
                            </div>
                            <div>
                                <div class="flex items-center justify-center gap-0.5 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 {{ $i <= ($artisanStats['average_rating'] ?? 0) ? 'text-amber-400 fill-current' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-500">Note moyenne</p>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-gray-900">{{ $artisanStats['projects_count'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Réalisations</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <i data-lucide="briefcase" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-600 mb-4">Vous n'avez pas encore de profil artisan</p>
                            <a href="{{ route('artisan.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Devenir artisan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>