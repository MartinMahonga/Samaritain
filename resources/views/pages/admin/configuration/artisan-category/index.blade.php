@extends('layouts.dashboard')

@section('title', 'Catégories d\'artisans')

@section('content')
    @if (!$categories->isEmpty())
        <div class="flex justify-between items-center">
            <h1 class="text-gray-800 dark:text-white">Catégories d'artisans</h1>
            <x-btn href="{{ route('admin.configuration.artisan-category.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Ajouter une catégorie
            </x-btn>
        </div>

        <!-- Recherche -->
        <form method="GET" action="{{ route('admin.configuration.artisan-category.index') }}" class="flex items-center gap-2">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une catégorie..."
                    class="w-full h-9 rounded-lg text-sm border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 pl-10 pr-4 py-2 text-gray-800 dark:text-white focus:outline-hidden focus:ring-2 focus:border-primary dark:focus:border-primary focus:ring-primary/10 dark:focus:ring-primary/20">
            </div>
            <x-btn type="submit" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                Rechercher
            </x-btn>
            @if (request('search'))
                <a href="{{ route('admin.configuration.artisan-category.index') }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-primary">
                    Réinitialiser
                </a>
            @endif
        </form>

        <x-container-dashed>
            <div x-data="categoryActions()" @keydown.escape="closeDeleteModal()">
                <div class="overflow-x-auto bg-sidebar dark:bg-gray-800 rounded-lg shadow-sm">
                    <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                        <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Nom</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-center">Ordre</th>
                                <th class="px-4 py-3 text-center">Utilisation</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($category->is_active)
                                            <span class="px-2 py-1 text-xs font-medium text-green-500 bg-green-300 dark:bg-green-900/30 dark:text-green-400 rounded-full">Actif</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-red-500 bg-red-300 dark:bg-red-900/30 dark:text-red-400 rounded-full">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-800 dark:text-white">{{ $category->sort_order }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-full">
                                            {{ $category->artisans_count }} artisan(s)
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.configuration.artisan-category.edit', $category) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300" title="Modifier">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <form action="{{ route('admin.configuration.artisan-category.toggle-active', $category) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="block text-yellow-500 dark:text-yellow-400 hover:text-yellow-600 dark:hover:text-yellow-300" title="{{ $category->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i data-lucide="{{ $category->is_active ? 'toggle-right' : 'toggle-left' }}" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                            <button x-on:click="openDeleteModal('{{ route('admin.configuration.artisan-category.destroy', $category) }}', '{{ $category->name }}', {{ $category->artisans_count }})" class="block text-destructive dark:text-red-400 hover:text-red-600 dark:hover:text-red-300" title="Supprimer">
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
                    {{ $categories->links() }}
                </div>

                <!-- Modal de confirmation de suppression -->
                <div x-cloak x-show="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70" x-on:click.self="closeDeleteModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg" x-on:click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supprimer la catégorie</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Êtes-vous sûr de vouloir supprimer <strong x-text="categoryName" class="text-gray-800 dark:text-white"></strong> ? Cette action est irréversible.
                                </p>
                                <p x-show="usageCount > 0" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    ⚠️ Cette catégorie est utilisée par <span x-text="usageCount"></span> artisan(s). Vous pouvez la désactiver au lieu de la supprimer.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <x-btn x-on:click="closeDeleteModal()" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
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
    @else
        <div class="flex justify-between">
            <div></div>
            <x-btn href="{{ route('admin.configuration.artisan-category.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Ajouter la première catégorie
            </x-btn>
        </div>
        <x-empty title="Aucune catégorie trouvée" description="Créez une première catégorie pour commencer" class="dark:text-gray-400">
            <x-slot:icon>
                <i data-lucide="drill" class="text-gray-400 dark:text-gray-500"></i>
            </x-slot:icon>
        </x-empty>
    @endif

    <script>
        function categoryActions() {
            return {
                isDeleteModalOpen: false,
                deleteAction: '',
                categoryName: '',
                usageCount: 0,
                openDeleteModal(action, name, count) {
                    this.deleteAction = action;
                    this.categoryName = name;
                    this.usageCount = count;
                    this.isDeleteModalOpen = true;
                },
                closeDeleteModal() {
                    this.isDeleteModalOpen = false;
                    this.deleteAction = '';
                    this.categoryName = '';
                    this.usageCount = 0;
                }
            }
        }
    </script>
@endsection