@extends('layouts.dashboard')

@section('title', 'Gestion des Pass')

@section('content')
    @if (!$passes->isEmpty())
        <div class="flex justify-between">
            <h1>Gestion des Pass</h1>
            <x-btn href="{{ route('passes.create') }}">
                <x-slot:prefix>
                    <i data-lucide="ticket-plus"></i>
                </x-slot:prefix>
                Nouveau Pass
            </x-btn>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                <div class="text-sm text-blue-100">Total</div>
                <div class="text-2xl font-bold">{{ $statistics['total'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 text-white">
                <div class="text-sm text-emerald-100">Actifs</div>
                <div class="text-2xl font-bold">{{ $statistics['active'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-4 text-white">
                <div class="text-sm text-red-100">Expirés</div>
                <div class="text-2xl font-bold">{{ $statistics['expired'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 text-white">
                <div class="text-sm text-amber-100">Utilisés</div>
                <div class="text-2xl font-bold">{{ $statistics['used'] }}</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                <div class="text-sm text-purple-100">Visites</div>
                <div class="text-2xl font-bold">{{ $statistics['total_visits'] }}</div>
            </div>
        </div>

        <!-- Filtres -->
        {{-- <div class="bg-sidebar rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[150px]">
                    <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ ($filters['status'] ?? '') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="expiré" {{ ($filters['status'] ?? '') == 'expiré' ? 'selected' : '' }}>Expiré</option>
                        <option value="utilisé" {{ ($filters['status'] ?? '') == 'utilisé' ? 'selected' : '' }}>Utilisé</option>
                        <option value="suspendu" {{ ($filters['status'] ?? '') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>
                <div class="flex-[2]">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" name="search" placeholder="Rechercher par nom, téléphone ou UUID"
                            value="{{ $filters['search'] ?? '' }}"
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <x-btn type="submit">
                    <x-slot:prefix>
                        <i data-lucide="funnel"></i>
                    </x-slot:prefix>
                    Filtrer
                </x-btn>
                @if(!empty($filters['status']) || !empty($filters['search']))
                    <a href="{{ route('passes.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">
                        Réinitialiser
                    </a>
                @endif
            </form>
        </div> --}}

        <x-container-dashed>
            <div x-data="passActions()" @keydown.escape="closeModal()">
                <div class="overflow-x-auto bg-sidebar rounded-lg shadow-sm">
                    <table class="w-full text-xs text-gray-600">
                        <thead class="border-b border-b-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left">Titulaire</th>
                                <th class="px-4 py-3 text-left">Contact</th>
                                <th class="px-4 py-3 text-left">Visites</th>
                                <th class="px-4 py-3 text-left">Période</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($passes as $pass)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $pass->holder_name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">UUID: {{ substr($pass->uuid, 0, 8) }}...</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm">{{ $pass->phone }}</div>
                                        <div class="text-xs text-gray-400">{{ $pass->email ?? 'Non renseigné' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <span class="font-medium">{{ $pass->remaining_visits }}</span>
                                            <span class="text-gray-400">/</span>
                                            <span>{{ $pass->allowed_visits }}</span>
                                            @if($pass->remaining_visits > 0 && $pass->status === 'actif')
                                                <span class="text-emerald-500 text-xs ml-1">●</span>
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1 mt-1">
                                            <div class="bg-blue-600 rounded-full h-1" 
                                                 style="width: {{ ($pass->remaining_visits / $pass->allowed_visits) * 100 }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-xs">
                                            <div>Début: {{ $pass->start_date->format('d/m/Y') }}</div>
                                            <div>Fin: {{ $pass->expiration_date->format('d/m/Y') }}</div>
                                            @php
                                                $daysLeft = now()->diffInDays($pass->expiration_date, false);
                                            @endphp
                                            @if($daysLeft <= 7 && $daysLeft > 0 && $pass->status === 'actif')
                                                <span class="text-amber-600 text-xs mt-1 inline-block">Expire dans {{ $daysLeft }} jour(s)</span>
                                            @endif
                                            @if($daysLeft <= 0 && $pass->status === 'actif')
                                                <span class="text-red-600 text-xs mt-1 inline-block">Expiré</span>
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
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-600">
                                            <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
                                            {{ ucfirst($pass->status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('passes.show', $pass) }}" class="block text-blue-500 hover:text-blue-700 transition" title="Voir">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('passes.edit', $pass) }}" class="block text-blue-500 hover:text-blue-700 transition" title="Modifier">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <button @click="openModal('{{ route('passes.destroy', $pass) }}', '{{ $pass->holder_name }}')" 
                                                    class="block text-destructive hover:text-red-700 transition" title="Supprimer">
                                                <i data-lucide="trash" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 mb-2 text-xs">
                    {{ $passes->withQueryString()->links() }}
                </div>

                <!-- Modal de confirmation de suppression -->
                <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background p-6 shadow-lg" @click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Supprimer le Pass</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Êtes-vous sûr de vouloir supprimer le Pass de <strong x-text="itemTitle"></strong> ?
                                </p>
                                <p class="mt-2 text-xs text-red-600">
                                    ⚠️ Attention : Cette action est irréversible et supprimera toutes les données associées.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <x-btn @click="closeModal()" style="outline">
                                Annuler
                            </x-btn>
                            <form :action="deleteAction" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-btn type="submit" style="destructive">
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
            <h1>Gestion des Pass</h1>
            <x-btn href="{{ route('passes.create') }}">
                <x-slot:prefix>
                    <i data-lucide="ticket-plus"></i>
                </x-slot:prefix>
                Nouveau Pass
            </x-btn>
        </div>

        <!-- Statistiques même quand vide -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                <div class="text-sm text-blue-100">Total</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 text-white">
                <div class="text-sm text-emerald-100">Actifs</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-4 text-white">
                <div class="text-sm text-red-100">Expirés</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 text-white">
                <div class="text-sm text-amber-100">Utilisés</div>
                <div class="text-2xl font-bold">0</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                <div class="text-sm text-purple-100">Visites</div>
                <div class="text-2xl font-bold">0</div>
            </div>
        </div>

        <x-empty title="Aucun Pass trouvé" description="Créez votre premier Pass pour commencer à gérer les accès">
            <x-slot:icon>
                <i data-lucide="ticket"></i>
            </x-slot:icon>
            <x-slot:actions>
                <x-btn href="{{ route('passes.create') }}">
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