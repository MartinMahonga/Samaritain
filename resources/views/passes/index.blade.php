@extends('layouts.dashboard')

@section('title', 'Gestion des Pass')

@section('content')
    @if (!$passes->isEmpty())
        <div class="flex justify-between">
            <h1 class="text-gray-800 dark:text-white">Gestion des Pass</h1>
            <x-btn href="{{ route('passes.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="ticket-plus"></i>
                </x-slot:prefix>
                Nouveau Pass
            </x-btn>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl p-4 text-white">
                <div class="text-sm text-blue-100">Total</div>
                <div class="text-2xl font-bold">{{ $statistics['total'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-xl p-4 text-white">
                <div class="text-sm text-emerald-100">Actifs</div>
                <div class="text-2xl font-bold">{{ $statistics['active'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 rounded-xl p-4 text-white">
                <div class="text-sm text-red-100">Expirés</div>
                <div class="text-2xl font-bold">{{ $statistics['expired'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 rounded-xl p-4 text-white">
                <div class="text-sm text-amber-100">Utilisés</div>
                <div class="text-2xl font-bold">{{ $statistics['used'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-xl p-4 text-white">
                <div class="text-sm text-purple-100">Visites</div>
                <div class="text-2xl font-bold">{{ $statistics['total_visits'] }}</div>
            </div>
        </div>

        <x-container-dashed>
            <div x-data="passActions()" @keydown.escape="closeModal()">
                <div class="overflow-x-auto bg-sidebar dark:bg-gray-800 rounded-lg shadow-sm">
                    <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                        <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Titulaire</th>
                                <th class="px-4 py-3 text-left">Contact</th>
                                <th class="px-4 py-3 text-left">Visites</th>
                                <th class="px-4 py-3 text-left">Période</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($passes as $pass)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $pass->holder_name }}</div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 font-mono">UUID: {{ substr($pass->uuid, 0, 8) }}...</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-800 dark:text-gray-300">{{ $pass->phone }}</div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500">{{ $pass->email ?? 'Non renseigné' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <span class="font-medium text-gray-800 dark:text-white">{{ $pass->remaining_visits }}</span>
                                            <span class="text-gray-400 dark:text-gray-500">/</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ $pass->allowed_visits }}</span>
                                            @if($pass->remaining_visits > 0 && $pass->status === 'actif')
                                                <span class="text-emerald-500 text-xs ml-1">●</span>
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1 mt-1">
                                            <div class="bg-blue-600 dark:bg-blue-500 rounded-full h-1" 
                                                 style="width: {{ ($pass->remaining_visits / $pass->allowed_visits) * 100 }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-700 dark:text-gray-300">
                                            <div>Début: {{ $pass->start_date->format('d/m/Y') }}</div>
                                            <div>Fin: {{ $pass->expiration_date->format('d/m/Y') }}</div>
                                            @php
                                                $daysLeft = now()->diffInDays($pass->expiration_date, false);
                                            @endphp
                                            @if($daysLeft <= 7 && $daysLeft > 0 && $pass->status === 'actif')
                                                <span class="text-amber-600 dark:text-amber-400 text-xs mt-1 inline-block">Expire dans {{ $daysLeft }} jour(s)</span>
                                            @endif
                                            @if($daysLeft <= 0 && $pass->status === 'actif')
                                                <span class="text-red-600 dark:text-red-400 text-xs mt-1 inline-block">Expiré</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusConfig = [
                                                'actif' => ['color' => 'emerald', 'icon' => 'check-circle'],
                                                'expiré' => ['color' => 'red', 'icon' => 'calendar-x'],
                                                'utilisé' => ['color' => 'amber', 'icon' => 'check'],
                                                'suspendu' => ['color' => 'gray', 'icon' => 'ban'],
                                            ];
                                            $config = $statusConfig[$pass->status] ?? ['color' => 'gray', 'icon' => 'help-circle'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400">
                                            <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
                                            {{ ucfirst($pass->status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('passes.show', $pass) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition" title="Voir">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('passes.edit', $pass) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition" title="Modifier">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <button @click="openModal('{{ route('passes.destroy', $pass) }}', '{{ $pass->holder_name }}')" 
                                                    class="block text-destructive dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition" title="Supprimer">
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
                    {{ $passes->withQueryString()->links() }}
                </div>

                <!-- Modal de confirmation de suppression -->
                <div x-cloak x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70" @click.self="closeModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg" @click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supprimer le Pass</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Êtes-vous sûr de vouloir supprimer le Pass de <strong x-text="itemTitle" class="text-gray-800 dark:text-white"></strong> ?
                                </p>
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">
                                    Attention : Cette action est irréversible et supprimera toutes les données associées.
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
    @else
        <div class="flex justify-between">
            <h1 class="text-gray-800 dark:text-white">Gestion des Pass</h1>
            <x-btn href="{{ route('passes.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="ticket-plus"></i>
                </x-slot:prefix>
                Nouveau Pass
            </x-btn>
        </div>

        <!-- Statistiques même quand vide -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl p-4 text-white">
                <div class="text-sm text-blue-100">Total</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-xl p-4 text-white">
                <div class="text-sm text-emerald-100">Actifs</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-600 dark:from-red-600 dark:to-red-700 rounded-xl p-4 text-white">
                <div class="text-sm text-red-100">Expirés</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 rounded-xl p-4 text-white">
                <div class="text-sm text-amber-100">Utilisés</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-xl p-4 text-white">
                <div class="text-sm text-purple-100">Visites</div>
                <div class="text-2xl font-bold">0</div>
            </div>
        </div>

        <x-empty title="Aucun Pass trouvé" description="Créez votre premier Pass pour commencer à gérer les accès" class="dark:text-gray-400">
            <x-slot:icon>
                <i data-lucide="ticket" class="text-gray-400 dark:text-gray-500"></i>
            </x-slot:icon>
            <x-slot:actions>
                <x-btn href="{{ route('passes.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                    <x-slot:prefix>
                        <i data-lucide="plus"></i>
                    </x-slot:prefix>
                    Créer le premier Pass
                </x-btn>
            </x-slot:actions>
        </x-empty>
    @endif

    <script>
        function passActions() {
            return {
                isOpen: false,
                deleteAction: '',
                itemTitle: '',
                openModal(action, title) {
                    this.deleteAction = action;
                    this.itemTitle = title;
                    this.isOpen = true;
                },
                closeModal() {
                    this.isOpen = false;
                    this.deleteAction = '';
                    this.itemTitle = '';
                }
            }
        }
    </script>
@endsection