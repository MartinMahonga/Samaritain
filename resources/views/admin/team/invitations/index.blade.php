@extends('layouts.dashboard')

@section('title', 'Invitations en attente')

@section('content')
    @if (!$invitations->isEmpty())
        <div class="flex justify-between">
            <h1 class="dark:text-white">Liste des invitations</h1>
            <x-btn href="{{ route('admin.invitations.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Nouvelle invitation
            </x-btn>
        </div>
        <x-container-dashed class="dark:border-gray-700">
            <div x-data="invitationActions()" @keydown.escape="closeCancelModal()">
                <div class="overflow-x-auto bg-sidebar dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/50">
                    <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                        <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Email</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Rôle</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Expire le</th>
                                <th class="px-4 py-3 text-left dark:text-gray-300">Statut</th>
                                <th class="px-4 py-3 text-center dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($invitations as $inv)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3 dark:text-gray-300">{{ $inv->email }}</td>
                                    <td class="px-4 py-3 dark:text-gray-300">{{ $inv->role->name }}</td>
                                    <td class="px-4 py-3 dark:text-gray-300">{{ $inv->expires_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        @if ($inv->accepted_at)
                                            <span class="px-2 py-1 text-xs font-medium text-green-500 dark:text-green-400 bg-green-300 dark:bg-green-900/30 rounded-full">Acceptée</span>
                                        @elseif ($inv->cancelled_at)
                                            <span class="px-2 py-1 text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-300 dark:bg-gray-900/30 rounded-full">Annulée</span>
                                        @elseif ($inv->isExpired())
                                            <span class="px-2 py-1 text-xs font-medium text-red-500 dark:text-red-400 bg-red-300 dark:bg-red-900/30 rounded-full">Expirée</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-yellow-500 dark:text-yellow-400 bg-yellow-300 dark:bg-yellow-900/30 rounded-full">En attente</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        @if ($inv->isValid())
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('admin.invitations.resend', $inv) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="block text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition"
                                                        title="Renvoyer l'invitation">
                                                        <i data-lucide="send" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                                <button x-on:click="openCancelModal('{{ route('admin.invitations.destroy', $inv) }}', '{{ $inv->email }}')"
                                                    class="block text-destructive dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition"
                                                    title="Annuler l'invitation">
                                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 mb-2 text-xs text-gray-600 dark:text-gray-400">
                    {{ $invitations->links() }}
                </div>

                <!-- Modal de confirmation d'annulation -->
                <div x-cloak x-show="isCancelModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70" x-on:click.self="closeCancelModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg dark:shadow-gray-900/70" x-on:click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Annuler l'invitation</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Êtes-vous sûr de vouloir annuler l'invitation pour <strong x-text="invitationEmail" class="text-gray-800 dark:text-white"></strong> ? Cette action est irréversible.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <x-btn x-on:click="closeCancelModal()" style="outline" class="dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                Annuler
                            </x-btn>
                            <form :action="cancelAction" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-btn type="submit" style="destructive" class="dark:bg-red-600 dark:hover:bg-red-700 dark:text-white">
                                    Confirmer l'annulation
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
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Créer la première invitation
            </x-btn>
        </div>
        <x-empty title="Aucune invitation trouvée" description="Créez une première invitation pour commencer" class="dark:text-gray-400">
            <x-slot:icon>
                <i data-lucide="mail" class="text-gray-400 dark:text-gray-500"></i>
            </x-slot:icon>
        </x-empty>
    @endif

    <script>
        function invitationActions() {
            return {
                isCancelModalOpen: false,
                cancelAction: '',
                invitationEmail: '',
                openCancelModal(action, email) {
                    this.cancelAction = action;
                    this.invitationEmail = email;
                    this.isCancelModalOpen = true;
                },
                closeCancelModal() {
                    this.isCancelModalOpen = false;
                    this.cancelAction = '';
                    this.invitationEmail = '';
                }
            }
        }
    </script>
@endsection