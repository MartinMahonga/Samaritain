@extends('layouts.dashboard')

@section('title', 'Membres de l\'équipe')

@section('content')
    @if (!$members->isEmpty())
        <div class="flex justify-between">
            <h1 class="dark:text-white">Liste des membres</h1>
            <x-btn href="{{ route('admin.invitations.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="user-plus"></i>
                </x-slot:prefix>
                Inviter un membre
            </x-btn>
        </div>
        <x-container-dashed class="dark:border-gray-700">
            <div x-data="memberActions()" @keydown.escape="closeActionModal()">
                <!-- Barre de recherche -->
                <div class="mb-4">
                    <div class="relative">
                        <i data-lucide="search"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                        <input type="text" id="search" placeholder="Rechercher un membre..."
                            class="w-full md:w-96 pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                </div>

                <div class="overflow-x-auto bg-sidebar dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/50">
                    <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                        <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Photo</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Nom</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Email</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Rôle</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Statut</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Date d'ajout</th>
                                <th class="px-4 py-3 text-center dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($members as $member)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3">
                                        <img src="{{ $member->profileUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=3b82f6&color=fff' }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    </td>
                                    <td class="px-4 py-3 font-medium dark:text-white">{{ $member->name }}</td>
                                    <td class="px-4 py-3 dark:text-gray-300">{{ $member->email }}</td>
                                    <td class="px-4 py-3">
                                        @if ($member->roles->first())
                                            <span
                                                class="px-2 py-1 text-xs font-medium text-blue-500 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                                {{ $member->roles->first()->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Aucun</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($member->is_active)
                                            <span
                                                class="px-2 py-1 text-xs font-medium text-green-500 dark:text-green-400 bg-green-100 dark:bg-green-900/30 rounded-full">Actif</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-medium text-red-500 dark:text-red-400 bg-red-100 dark:bg-red-900/30 rounded-full">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 dark:text-gray-300">{{ $member->created_at->format('d/m/Y') }}</td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.members.show', $member) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 transition"
                                                title="Voir">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('admin.members.edit', $member) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 transition"
                                                title="Modifier">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            @if ($member->is_active)
                                                <button
                                                    x-on:click="openActionModal('deactivate', '{{ route('admin.members.deactivate', $member) }}', '{{ $member->name }}')"
                                                    class="block text-orange-500 dark:text-orange-400 hover:text-orange-600 dark:hover:text-orange-300 transition" title="Désactiver">
                                                    <i data-lucide="user-x" class="w-4 h-4"></i>
                                                </button>
                                            @else
                                                <button
                                                    x-on:click="openActionModal('activate', '{{ route('admin.members.activate', $member) }}', '{{ $member->name }}')"
                                                    class="block text-green-500 dark:text-green-400 hover:text-green-600 dark:hover:text-green-300 transition" title="Réactiver">
                                                    <i data-lucide="user-check" class="w-4 h-4"></i>
                                                </button>
                                            @endif
                                            <button
                                                x-on:click="openActionModal('delete', '{{ route('admin.members.destroy', $member) }}', '{{ $member->name }}')"
                                                class="block text-destructive dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition" title="Supprimer">
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
                    {{ $members->links() }}
                </div>

                <!-- Modal de confirmation -->
                <div x-cloak x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70"
                    x-on:click.self="closeActionModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg dark:shadow-gray-900/70" x-on:click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full"
                                :class="{
                                    'bg-red-100 dark:bg-red-900/30': actionType === 'delete',
                                    'bg-orange-100 dark:bg-orange-900/30': actionType === 'deactivate',
                                    'bg-green-100 dark:bg-green-900/30': actionType === 'activate'
                                }">
                                <i :class="{
                                    'lucide-alert-octagon text-red-600 dark:text-red-400': actionType === 'delete',
                                    'lucide-user-x text-orange-600 dark:text-orange-400': actionType === 'deactivate',
                                    'lucide-user-check text-green-600 dark:text-green-400': actionType === 'activate'
                                }"
                                    class="h-6 w-6"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="modalTitle"></h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span x-html="modalMessage" class="dark:text-gray-300"></span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <x-btn x-on:click="closeActionModal()" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                Annuler
                            </x-btn>
                            <form :action="actionUrl" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="_method" :value="methodType">
                                <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                                    <span x-text="confirmButtonText"></span>
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
            <x-btn href="{{ route('admin.invitations.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="user-plus"></i>
                </x-slot:prefix>
                Inviter le premier membre
            </x-btn>
        </div>
        <x-empty title="Aucun membre trouvé"
            description="Invitez votre premier membre pour commencer à constituer votre équipe" class="dark:text-gray-400">
            <x-slot:icon>
                <i data-lucide="users" class="text-gray-400 dark:text-gray-500"></i>
            </x-slot:icon>
        </x-empty>
    @endif

    <script>
        function memberActions() {
            return {
                isModalOpen: false,
                actionType: '',
                actionUrl: '',
                memberName: '',
                methodType: 'PATCH',

                get modalTitle() {
                    if (this.actionType === 'delete') return 'Supprimer le membre';
                    if (this.actionType === 'deactivate') return 'Désactiver le membre';
                    return 'Réactiver le membre';
                },

                get modalMessage() {
                    if (this.actionType === 'delete') {
                        return `Êtes-vous sûr de vouloir supprimer définitivement <strong class="text-gray-800 dark:text-white">${this.memberName}</strong> ? Cette action est irréversible.`;
                    }
                    if (this.actionType === 'deactivate') {
                        return `Êtes-vous sûr de vouloir désactiver <strong class="text-gray-800 dark:text-white">${this.memberName}</strong> ? L'utilisateur ne pourra plus se connecter.`;
                    }
                    return `Êtes-vous sûr de vouloir réactiver <strong class="text-gray-800 dark:text-white">${this.memberName}</strong> ? L'utilisateur pourra à nouveau se connecter.`;
                },

                get confirmButtonText() {
                    if (this.actionType === 'delete') return 'Supprimer';
                    if (this.actionType === 'deactivate') return 'Désactiver';
                    return 'Réactiver';
                },

                openActionModal(type, url, name) {
                    this.actionType = type;
                    this.actionUrl = url;
                    this.memberName = name;

                    if (type === 'delete') {
                        this.methodType = 'DELETE';
                    } else {
                        this.methodType = 'PATCH';
                    }

                    this.isModalOpen = true;
                },

                closeActionModal() {
                    this.isModalOpen = false;
                    this.actionType = '';
                    this.actionUrl = '';
                    this.memberName = '';
                    this.methodType = 'PATCH';
                }
            }
        }
    </script>
@endsection