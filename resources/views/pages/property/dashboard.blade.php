@extends('layouts.base')

@section('title', 'Mon tableau de bord')

@section('content')
    <x-blade-components::layout.container>
        <div class="container mx-auto px-4 py-8">
            <!-- En-tête -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">Mon tableau de bord</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Gérez vos biens immobiliers en un clin d'œil</p>
                </div>
                <x-btn href="{{ route('property.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                    <x-slot:prefix>
                        <i data-lucide="plus"></i>
                    </x-slot:prefix>
                    Ajouter un bien
                </x-btn>
            </div>
    
            <!-- Statistiques avec cartes améliorées -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <!-- Total des biens -->
                <div
                    class="group bg-sidebar dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5 border border-accent dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total des biens</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="building-2" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    @if ($stats['total'] > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                <span class="text-green-600 dark:text-green-400 font-medium">{{ $stats['active'] }} actifs</span>
                            </p>
                        </div>
                    @endif
                </div>
    
                <!-- En attente -->
                <div
                    class="group bg-sidebar dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5 border border-accent dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">En attente</p>
                            <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['pending'] }}</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="hourglass" class="w-6 h-6 text-amber-600 dark:text-amber-400"></i>
                        </div>
                    </div>
                    @if ($stats['pending'] > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-amber-600 dark:text-amber-400">En attente de validation</p>
                        </div>
                    @endif
                </div>
    
                <!-- Vérifiés -->
                <div
                    class="group bg-sidebar dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5 border border-accent dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Vérifiés</p>
                            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['verified'] }}</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                    </div>
                    @if ($stats['verified'] > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-emerald-600 dark:text-emerald-400">Validés par l'administrateur</p>
                        </div>
                    @endif
                </div>
    
                <!-- Actifs -->
                <div
                    class="group bg-sidebar dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5 border border-accent dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Actifs</p>
                            <p class="text-3xl font-bold text-teal-600 dark:text-teal-400 mt-1">{{ $stats['active'] }}</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="eye" class="w-6 h-6 text-teal-600 dark:text-teal-400"></i>
                        </div>
                    </div>
                    @if ($stats['active'] > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-teal-600 dark:text-teal-400">Visible sur le site</p>
                        </div>
                    @endif
                </div>
            </div>
    
            <!-- Liste des biens -->
            <div class="rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Mes biens</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Gérez et suivez l'état de vos annonces</p>
                        </div>
                        @if ($properties->count() > 0)
                            <span class="text-sm text-gray-400 dark:text-gray-500">{{ $properties->total() }} bien(s) au total</span>
                        @endif
                    </div>
                </div>
    
                <div class="px-2 py-2 bg-white dark:bg-gray-900">
                    @if (!$properties->isEmpty())
                        <x-container-dashed>
                            <div x-data="deleteModal()" @keydown.escape="closeModal()">
                                <div class="overflow-x-auto bg-sidebar dark:bg-gray-800 rounded-lg shadow-sm">
                                    <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                                        <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left">ID</th>
                                                <th class="px-4 py-3 text-left">Titre</th>
                                                <th class="px-4 py-3 text-left">Prix</th>
                                                <th class="px-4 py-3 text-left">Pièces</th>
                                                <th class="px-4 py-3 text-left">Ville</th>
                                                <th class="px-4 py-3 text-left">Statut</th>
                                                <th class="px-4 py-3 text-left">Vérifié</th>
                                                <th class="px-4 py-3 text-left">Actif</th>
                                                <th class="px-4 py-3 text-center">Actions</th>
                                            </tr>
                                        </thead>
        
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                            @foreach ($properties as $property)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                    <td class="px-4 py-3">#{{ $loop->iteration }}</td>
                                                    <td class="px-4 py-3 font-medium hover:text-blue-600 dark:hover:text-blue-400">
                                                        <a href="{{ route('property.show', $property) }}" class="text-gray-800 dark:text-white">{{ $property->title }}</a>
                                                    </td>
                                                    <td class="px-4 py-3 text-gray-800 dark:text-white">{{ number_format($property->price, 0, ',', ' ') }}</td>
                                                    <td class="px-4 py-3">{{ $property->rooms }}</td>
                                                    <td class="px-4 py-3">{{ $property->city->name ?? '-' }}</td>
                                                    <td class="px-4 py-3">
                                                        @switch($property->status->value)
                                                            @case('available')
                                                                <span
                                                                    class="px-2 py-1 text-xs font-medium text-green-500 dark:text-green-400 bg-green-300 dark:bg-green-900/30 rounded-full">disponible</span>
                                                            @break
        
                                                            @case('sold')
                                                                <span
                                                                    class="px-2 py-1 text-xs font-medium text-red-500 dark:text-red-400 bg-red-300 dark:bg-red-900/30 rounded-full">vendu</span>
                                                            @break
        
                                                            @default
                                                                <span
                                                                    class="px-2 py-1 text-xs font-medium text-blue-500 dark:text-blue-400 bg-blue-300 dark:bg-blue-900/30 rounded-full">loué</span>
                                                        @endswitch
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        @if ($property->is_verify)
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium text-green-500 dark:text-green-400 bg-green-300 dark:bg-green-900/30 rounded-full">
                                                                <i data-lucide="check-circle" class="inline w-3 h-3"></i> Oui
                                                            </span>
                                                        @else
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium text-yellow-500 dark:text-yellow-400 bg-yellow-300 dark:bg-yellow-900/30 rounded-full">
                                                                <i data-lucide="clock" class="inline w-3 h-3"></i> Non
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        @if ($property->is_active)
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium text-green-500 dark:text-green-400 bg-green-300 dark:bg-green-900/30 rounded-full">
                                                                <i data-lucide="eye" class="inline w-3 h-3"></i> Actif
                                                            </span>
                                                        @else
                                                            <span
                                                                class="px-2 py-1 text-xs font-medium text-red-500 dark:text-red-400 bg-red-300 dark:bg-red-900/30 rounded-full">
                                                                <i data-lucide="eye-off" class="inline w-3 h-3"></i> Inactif
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <a href="{{ route('property.show', $property) }}"
                                                                class="block text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" title="Voir">
                                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                                            </a>
                                                            <a href="{{ route('property.edit', $property) }}"
                                                                class="block text-yellow-500 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300" title="Modifier">
                                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                                            </a>
                                                            <form action="{{ route('property.duplicate', $property) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="block text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                                                    title="Dupliquer">
                                                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                                                </button>
                                                            </form>
                                                            <button
                                                                @click="openModal('{{ route('property.destroy', $property) }}', '{{ $property->title }}')"
                                                                class="block text-destructive dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" title="Supprimer">
                                                                <i data-lucide="trash" class="w-4 h-4"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 mb-2 text-xs text-gray-600 dark:text-gray-400">
                                    {{ $properties->links() }}
                                </div>
        
                                <!-- Modal de confirmation de suppression -->
                                <div x-cloak x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70"
                                    @click.self="closeModal()">
                                    <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg" @click.stop>
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supprimer le bien</h3>
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                    Êtes-vous sûr de vouloir supprimer <strong x-text="propertyTitle" class="text-gray-800 dark:text-white"></strong> ?
                                                    Cette action est irréversible.
                                                </p>
                                            </div>
                                        </div>
        
                                        <div class="mt-6 flex items-center justify-end gap-3">
                                            <x-btn @click="closeModal()" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                                Annuler
                                            </x-btn>
                                            <form :action="deleteAction" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <x-btn type="submit" style="destructive" class="dark:bg-red-600 dark:hover:bg-red-700 dark:text-white">
                                                    Supprimer
                                                </x-btn>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-container-dashed>
        
                        <script>
                            function deleteModal() {
                                return {
                                    isOpen: false,
                                    deleteAction: '',
                                    propertyTitle: '',
                                    openModal(action, title) {
                                        this.deleteAction = action;
                                        this.propertyTitle = title;
                                        this.isOpen = true;
                                    },
                                    closeModal() {
                                        this.isOpen = false;
                                        this.deleteAction = '';
                                        this.propertyTitle = '';
                                    }
                                }
                            }
                        </script>
                    @else
                        <x-empty title="Aucun bien trouvé" description="Créer un premier bien pour commencer" class="dark:text-gray-400">
                            <x-slot:icon>
                                <i data-lucide="building" class="text-gray-400 dark:text-gray-500"></i>
                            </x-slot:icon>
                        </x-empty>
                    @endif
                </div>
            </div>
    
            <!-- Conseils rapides -->
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 rounded-xl p-5 border border-blue-100 dark:border-blue-900/30">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Conseils pour maximiser vos ventes</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Ajoutez des photos de qualité, une description détaillée et un prix compétitif.
                            Les biens vérifiés par notre équipe apparaissent en priorité dans les recherches.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-blade-components::layout.container>
@endsection