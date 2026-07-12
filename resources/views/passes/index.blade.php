@extends('layouts.dashboard')

@section('title', 'Gestion des Pass')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Gestion des Pass</h1>
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

    <!-- Barre de recherche & Filtres -->
    <form method="GET" action="{{ route('passes.index') }}" class="mb-6 flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label for="search" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Rechercher</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </span>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nom, téléphone, UUID, référence..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition outline-none">
            </div>
        </div>
        <div class="w-full md:w-64">
            <label for="filter" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Filtrer par</label>
            <select name="filter" id="filter" onchange="this.form.submit()"
                class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition outline-none">
                <option value="all" {{ request('filter') === 'all' || !request('filter') ? 'selected' : '' }}>Tous</option>
                <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="expired" {{ request('filter') === 'expired' ? 'selected' : '' }}>Expirés</option>
                <option value="manual" {{ request('filter') === 'manual' ? 'selected' : '' }}>Créés manuellement</option>
                <option value="generated" {{ request('filter') === 'generated' ? 'selected' : '' }}>Générés après paiement</option>
                <option value="paid" {{ request('filter') === 'paid' ? 'selected' : '' }}>Payés</option>
                <option value="payment_pending" {{ request('filter') === 'payment_pending' ? 'selected' : '' }}>Paiement en attente</option>
                <option value="payment_failed" {{ request('filter') === 'payment_failed' ? 'selected' : '' }}>Paiement échoué</option>
            </select>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <x-btn type="submit" class="w-full md:w-auto dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                Filtrer
            </x-btn>
            @if(request('search') || request('filter'))
                <a href="{{ route('passes.index') }}" class="inline-flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition w-full md:w-auto text-sm font-medium">
                    Réinitialiser
                </a>
            @endif
        </div>
    </form>

    @if (!$passes->isEmpty())
        <x-container-dashed>
            <div x-data="passActions()" @keydown.escape="closeModal()">
                <div class="overflow-x-auto bg-sidebar dark:bg-gray-800 rounded-lg shadow-sm">
                    <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                        <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Titulaire</th>
                                <th class="px-4 py-3 text-left">Type / Propriété</th>
                                <th class="px-4 py-3 text-left">Contact</th>
                                <th class="px-4 py-3 text-left">Visites</th>
                                <th class="px-4 py-3 text-left">Période / Expiration</th>
                                <th class="px-4 py-3 text-left">Paiement</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($passes as $pass)
                                @php
                                    $isVisit = $pass instanceof \App\Models\VisitPass;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    {{-- Titulaire --}}
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $pass->holder_name }}</div>
                                        @if($isVisit)
                                            <div class="text-[0.65rem] text-primary dark:text-primary-400 font-semibold uppercase tracking-wider">Pass Visite • {{ $pass->reference }}</div>
                                        @else
                                            <div class="text-[0.65rem] text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-wider">Créé manuellement</div>
                                        @endif
                                        <div class="text-[0.65rem] text-gray-400 dark:text-gray-500 font-mono mt-0.5">UUID: {{ substr($pass->uuid, 0, 8) }}...</div>
                                    </td>

                                    {{-- Type / Propriétaire --}}
                                    <td class="px-4 py-3">
                                        @if($isVisit)
                                            <div class="text-xs text-gray-700 dark:text-gray-300">
                                                <span class="font-medium text-gray-500 dark:text-gray-400">Propriétaire:</span> {{ $pass->user->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                <span class="font-medium text-gray-500 dark:text-gray-400">Propriété:</span> {{ $pass->property->title ?? 'N/A' }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 italic">Administrateur</span>
                                        @endif
                                    </td>

                                    {{-- Contact --}}
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-800 dark:text-gray-300">{{ $pass->phone }}</div>
                                        <div class="text-[0.65rem] text-gray-400 dark:text-gray-500">{{ $pass->email ?? 'Non renseigné' }}</div>
                                    </td>

                                    {{-- Visites --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1">
                                            <span class="font-medium text-gray-800 dark:text-white">{{ $pass->remaining_visits }}</span>
                                            <span class="text-gray-400 dark:text-gray-500">/</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ $pass->allowed_visits }}</span>
                                            @if($pass->remaining_visits > 0 && ($isVisit ? $pass->isActive() : $pass->status === 'actif'))
                                                <span class="text-emerald-500 text-xs ml-1">●</span>
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1 mt-1">
                                            <div class="bg-blue-600 dark:bg-blue-500 rounded-full h-1"
                                                 style="width: {{ ($pass->remaining_visits / $pass->allowed_visits) * 100 }}%"></div>
                                        </div>
                                    </td>

                                    {{-- Période --}}
                                    <td class="px-4 py-3">
                                        <div class="text-xs text-gray-700 dark:text-gray-300">
                                            @if($isVisit)
                                                @if($pass->expires_at)
                                                    <div>Fin: {{ $pass->expires_at->format('d/m/Y H:i') }}</div>
                                                    @php
                                                        $daysLeft = now()->diffInDays($pass->expires_at, false);
                                                        $hoursLeft = now()->diffInHours($pass->expires_at, false);
                                                    @endphp
                                                    @if($pass->isActive())
                                                        @if($daysLeft > 0)
                                                            <span class="text-emerald-600 dark:text-emerald-400 text-[0.65rem] font-semibold mt-1 inline-block">Reste {{ $daysLeft }} jour(s)</span>
                                                        @elseif($hoursLeft > 0)
                                                            <span class="text-amber-600 dark:text-amber-400 text-[0.65rem] font-semibold mt-1 inline-block">Reste {{ $hoursLeft }} h</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500 italic">Pas encore activé</span>
                                                @endif
                                            @else
                                                <div>Début: {{ $pass->start_date->format('d/m/Y') }}</div>
                                                <div>Fin: {{ $pass->expiration_date->format('d/m/Y') }}</div>
                                                @php
                                                    $daysLeft = now()->diffInDays($pass->expiration_date, false);
                                                @endphp
                                                @if($daysLeft <= 7 && $daysLeft > 0 && $pass->status === 'actif')
                                                    <span class="text-amber-600 dark:text-amber-400 text-[0.65rem] font-semibold mt-1 inline-block">Expire dans {{ $daysLeft }} jour(s)</span>
                                                @endif
                                                @if($daysLeft <= 0 && $pass->status === 'actif')
                                                    <span class="text-red-600 dark:text-red-400 text-[0.65rem] font-semibold mt-1 inline-block">Expiré</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Paiement --}}
                                    <td class="px-4 py-3">
                                        @if($isVisit)
                                            <div class="text-xs text-gray-800 dark:text-gray-300 font-semibold">{{ number_format($pass->amount, 0, ',', ' ') }} FCFA</div>
                                            <div class="mt-0.5">
                                                @if($pass->isPaid())
                                                    <span class="inline-flex items-center text-[0.65rem] font-semibold text-emerald-600 dark:text-emerald-400">
                                                        <i data-lucide="check-circle" class="w-3 h-3 mr-0.5"></i> Payé
                                                    </span>
                                                @elseif($pass->isPendingPayment())
                                                    <span class="inline-flex items-center text-[0.65rem] font-semibold text-amber-600 dark:text-amber-400">
                                                        <i data-lucide="clock" class="w-3 h-3 mr-0.5"></i> En attente
                                                    </span>
                                                @elseif($pass->isPaymentFailed())
                                                    <span class="inline-flex items-center text-[0.65rem] font-semibold text-red-600 dark:text-red-400">
                                                        <i data-lucide="alert-triangle" class="w-3 h-3 mr-0.5"></i> Échoué
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 italic">Gratuit (Admin)</span>
                                        @endif
                                    </td>

                                    {{-- Statut --}}
                                    <td class="px-4 py-3">
                                        @php
                                            if ($isVisit) {
                                                $status = $pass->isExpired() ? 'expired' : $pass->status;
                                                $statusConfig = [
                                                    'pending_payment' => ['color' => 'amber', 'icon' => 'clock', 'label' => 'En attente'],
                                                    'active' => ['color' => 'emerald', 'icon' => 'check-circle', 'label' => 'Actif'],
                                                    'used' => ['color' => 'amber', 'icon' => 'check', 'label' => 'Utilisé'],
                                                    'expired' => ['color' => 'red', 'icon' => 'calendar-x', 'label' => 'Expiré'],
                                                    'cancelled' => ['color' => 'gray', 'icon' => 'ban', 'label' => 'Annulé'],
                                                    'payment_failed' => ['color' => 'red', 'icon' => 'alert-triangle', 'label' => 'Paiement échoué'],
                                                ];
                                                $config = $statusConfig[$status] ?? ['color' => 'gray', 'icon' => 'help-circle', 'label' => $status];
                                            } else {
                                                $statusConfig = [
                                                    'actif' => ['color' => 'emerald', 'icon' => 'check-circle', 'label' => 'Actif'],
                                                    'expiré' => ['color' => 'red', 'icon' => 'calendar-x', 'label' => 'Expiré'],
                                                    'utilisé' => ['color' => 'amber', 'icon' => 'check', 'label' => 'Utilisé'],
                                                    'suspendu' => ['color' => 'gray', 'icon' => 'ban', 'label' => 'Suspendu'],
                                                ];
                                                $config = $statusConfig[$pass->status] ?? ['color' => 'gray', 'icon' => 'help-circle', 'label' => $pass->status];
                                            }
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-[0.65rem] font-medium rounded-full bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400">
                                            <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
                                            {{ $config['label'] }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($isVisit)
                                                <a href="{{ route('my-visit-passes.show', $pass) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition" title="Voir">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </a>
                                            @else
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
                                            @endif
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
        <x-empty title="Aucun Pass trouvé" description="Aucun pass ne correspond à vos filtres de recherche." class="dark:text-gray-400">
            <x-slot:icon>
                <i data-lucide="ticket" class="text-gray-400 dark:text-gray-500"></i>
            </x-slot:icon>
            <x-slot:actions>
                @if(request('search') || request('filter'))
                    <a href="{{ route('passes.index') }}" class="inline-flex items-center justify-center border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Réinitialiser les filtres
                    </a>
                @else
                    <x-btn href="{{ route('passes.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                        <x-slot:prefix>
                            <i data-lucide="plus"></i>
                        </x-slot:prefix>
                        Créer le premier Pass
                    </x-btn>
                @endif
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