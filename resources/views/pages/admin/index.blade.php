@extends('layouts.dashboard')

@section('title', 'Tableau de bord')

@section('content')
    <!-- En-tête avec bienvenue -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Bonjour, {{ auth()->user()->name }} 👋</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Voici ce qui se passe sur votre plateforme aujourd'hui.</p>
    </div>

    <!-- Cartes de statistiques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total utilisateurs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-xl p-3">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">Total</span>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalUsers }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Utilisateurs inscrits</div>
            <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-2">+{{ $newThisWeek }} cette semaine</div>
        </div>

        <!-- Total biens -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-xl p-3">
                    <i data-lucide="building" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalProperties }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Biens immobiliers</div>
            <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ number_format($totalValue, 0, ',', ' ') }} FCFA</div>
        </div>

        <!-- Pass actifs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-purple-100 dark:bg-purple-900/30 rounded-xl p-3">
                    <i data-lucide="ticket" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $activePasses }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pass actifs</div>
            <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $totalScans }} scans effectués</div>
        </div>

        <!-- Taux d'activité -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="bg-amber-100 dark:bg-amber-900/30 rounded-xl p-3">
                    <i data-lucide="activity" class="w-6 h-6 text-amber-600 dark:text-amber-400"></i>
                </div>
                <i data-lucide="circle-check" class="w-4 h-4 text-emerald-500 dark:text-emerald-400"></i>
            </div>
            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $activityRate }}%</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Taux d'activité</div>
            <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">des passes</div>
        </div>
    </div>

    <!-- Statistiques détaillées des biens -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="home" class="w-5 h-5 opacity-80"></i>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Disponibles</span>
            </div>
            <div class="text-2xl font-bold">{{ $availableProperties }}</div>
            <div class="text-xs opacity-80 mt-1">biens disponibles</div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="check-circle" class="w-5 h-5 opacity-80"></i>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Vendus</span>
            </div>
            <div class="text-2xl font-bold">{{ $soldProperties }}</div>
            <div class="text-xs opacity-80 mt-1">biens vendus</div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="key" class="w-5 h-5 opacity-80"></i>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Loués</span>
            </div>
            <div class="text-2xl font-bold">{{ $rentedProperties }}</div>
            <div class="text-xs opacity-80 mt-1">biens loués</div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-xl p-4 text-white">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="trending-up" class="w-5 h-5 opacity-80"></i>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Valeur totale</span>
            </div>
            <div class="text-xl font-bold">{{ number_format($totalValue, 0, ',', ' ') }}</div>
            <div class="text-xs opacity-80 mt-1">FCFA</div>
        </div>
    </div>

    <!-- Graphique et actions rapides -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Top biens -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-white">Top 3 des biens les plus chers</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Classement par valeur</p>
                </div>
                <a href="{{ route('admin.property.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Voir tous →</a>
            </div>
            <div class="space-y-3">
                @forelse($topProperties as $property)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-lg flex items-center justify-center text-white">
                                <i data-lucide="building" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $property->title }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $property->surface }} m² · {{ $property->rooms }} pièces</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800 dark:text-white">{{ number_format($property->price, 0, ',', ' ') }} FCFA</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-100 dark:bg-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-900/30 text-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-600 dark:text-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-400">
                                {{ ucfirst($property->status->value) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i data-lucide="home" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Aucun bien enregistré</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Actions rapides</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.property.create') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition group">
                    <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-lg p-2 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/40 transition">
                        <i data-lucide="building" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white text-sm">Ajouter un bien</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Nouvelle propriété immobilière</p>
                    </div>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-emerald-500 dark:group-hover:text-emerald-400"></i>
                </a>
                
                <a href="{{ route('passes.create') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition group">
                    <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40 transition">
                        <i data-lucide="ticket" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white text-sm">Créer un Pass</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Ajouter un nouveau pass visiteur</p>
                    </div>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-blue-500 dark:group-hover:text-blue-400"></i>
                </a>
                
                <a href="{{ route('passes.index') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition group">
                    <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-2 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40 transition">
                        <i data-lucide="list" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white text-sm">Liste des passes</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Gérer tous les passes</p>
                    </div>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400 dark:text-gray-500 ml-auto group-hover:text-purple-500 dark:group-hover:text-purple-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Deuxième ligne de contenu -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Derniers biens ajoutés -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">Derniers biens ajoutés</h3>
                <i data-lucide="building" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
            </div>
            <div class="space-y-3">
                @forelse($recentProperties as $property)
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $property->title }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $property->address }} · {{ $property->surface }} m²</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ number_format($property->price, 0, ',', ' ') }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-100 dark:bg-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-900/30 text-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-600 dark:text-{{ $property->status->value === 'available' ? 'emerald' : ($property->status->value === 'sold' ? 'red' : 'blue') }}-400">
                                {{ ucfirst($property->status->value) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i data-lucide="building" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Aucun bien récent</p>
                    </div>
                @endforelse
                <div class="pt-2">
                    <a href="{{ route('admin.property.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                        Voir tous les biens →
                    </a>
                </div>
            </div>
        </div>

        <!-- Derniers passes créés -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">Derniers passes</h3>
                <i data-lucide="ticket" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
            </div>
            <div class="space-y-3">
                @forelse($recentPasses as $pass)
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $pass->holder_name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $pass->phone }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-xs rounded-full bg-{{ $pass->status === 'actif' ? 'emerald' : ($pass->status === 'expiré' ? 'red' : 'gray') }}-100 dark:bg-{{ $pass->status === 'actif' ? 'emerald' : ($pass->status === 'expiré' ? 'red' : 'gray') }}-900/30 text-{{ $pass->status === 'actif' ? 'emerald' : ($pass->status === 'expiré' ? 'red' : 'gray') }}-600 dark:text-{{ $pass->status === 'actif' ? 'emerald' : ($pass->status === 'expiré' ? 'red' : 'gray') }}-400">
                                {{ ucfirst($pass->status) }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $pass->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i data-lucide="ticket" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Aucun pass récent</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Derniers utilisateurs et scans -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Derniers utilisateurs inscrits -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">Derniers inscrits</h3>
                <i data-lucide="users" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
            </div>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i data-lucide="users" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Aucun utilisateur récent</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Derniers scans -->
        @if($recentScans->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white">Dernières activités de scan</h3>
                <i data-lucide="scan" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($recentScans as $scan)
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $scan->pass->holder_name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Scanné par {{ $scan->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $scan->scanned_at->format('d/m/Y H:i') }}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 block">{{ $scan->ip_address }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
@endsection