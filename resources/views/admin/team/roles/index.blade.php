@extends('layouts.dashboard')

@section('title', 'Rôles et permissions')

@section('content')
    @if (!$roles->isEmpty())
        <div class="flex justify-between">
            <h1 class="dark:text-white">Liste des rôles</h1>
            <x-btn href="{{ route('admin.roles.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="shield-plus"></i>
                </x-slot:prefix>
                Créer un rôle
            </x-btn>
        </div>
        <x-container-dashed class="dark:border-gray-700">
            <div x-data="roleActions()" @keydown.escape="closeDeleteModal()">
                <div class="overflow-x-auto bg-sidebar dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/50">
                    <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                        <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Rôle</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Permissions</th>
                                <th class="px-4 py-3 text-center dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($roles as $role)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3 font-medium dark:text-white">
                                        {{ ucfirst($role->name) }}
                                        @if($role->name === 'admin')
                                            <span class="ml-2 px-2 py-0.5 text-xs font-medium text-blue-500 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 rounded-full">Principal</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($role->permissions as $permission)
                                                <span class="px-2 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                            @if($role->permissions->isEmpty())
                                                <span class="text-gray-400 dark:text-gray-500">Aucune permission</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 transition">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            @if($role->name !== 'admin')
                                                <button x-on:click="openDeleteModal('{{ route('admin.roles.destroy', $role) }}', '{{ ucfirst($role->name) }}')" class="block text-destructive dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition">
                                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                                </button>
                                            @else
                                                <button class="block text-gray-300 dark:text-gray-600 cursor-not-allowed" disabled title="Le rôle admin ne peut pas être supprimé">
                                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal de confirmation de suppression -->
                <div x-cloak x-show="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70" x-on:click.self="closeDeleteModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg dark:shadow-gray-900/70" x-on:click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supprimer le rôle</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Êtes-vous sûr de vouloir supprimer le rôle <strong x-text="roleName" class="text-gray-800 dark:text-white"></strong> ? Cette action est irréversible.
                                </p>
                                @if(isset($role) && $role->users && $role->users->count() > 0)
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        ⚠️ Attention : Ce rôle est actuellement attribué à {{ $role->users->count() }} utilisateur(s).
                                    </p>
                                @endif
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
            <x-btn href="{{ route('admin.roles.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="shield-plus"></i>
                </x-slot:prefix>
                Créer le premier rôle
            </x-btn>
        </div>
        <x-empty title="Aucun rôle trouvé" description="Créez un premier rôle pour commencer à gérer les permissions" class="dark:text-gray-400">
            <x-slot:icon>
                <i data-lucide="shield" class="text-gray-400 dark:text-gray-500"></i>
            </x-slot:icon>
        </x-empty>
    @endif

    <script>
        function roleActions() {
            return {
                isDeleteModalOpen: false,
                deleteAction: '',
                roleName: '',
                openDeleteModal(action, name) {
                    this.deleteAction = action;
                    this.roleName = name;
                    this.isDeleteModalOpen = true;
                },
                closeDeleteModal() {
                    this.isDeleteModalOpen = false;
                    this.deleteAction = '';
                    this.roleName = '';
                }
            }
        }
    </script>
@endsection