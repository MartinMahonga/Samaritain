@extends('layouts.dashboard')

@section('title', 'Détail du Pass - ' . $pass->holder_name)

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('passes.index') }}" class="text-primary dark:text-primary-400 text-xs font-medium mb-2 inline-block hover:text-primary-700 dark:hover:text-primary-300">
                &larr; Retour à la liste
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-gray-800 dark:text-white">Détail du Pass</h1>
            <div class="flex gap-2">
                <x-btn href="{{ route('passes.export', $pass) }}" style="success" class="dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-700">
                    <x-slot:prefix>
                        <i data-lucide="file-pdf"></i>
                    </x-slot:prefix>
                    Exporter PDF
                </x-btn>
                <x-btn href="{{ route('passes.edit', $pass) }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                    <x-slot:prefix>
                        <i data-lucide="edit"></i>
                    </x-slot:prefix>
                    Modifier
                </x-btn>
            </div>
        </div>

        <!-- Informations du pass -->
        <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Colonne gauche -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">UUID</label>
                            <p class="text-sm font-mono text-gray-900 dark:text-white mt-1">{{ $pass->uuid }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Titulaire</label>
                            <p class="text-base font-semibold text-gray-900 dark:text-white mt-1">{{ $pass->holder_name }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Téléphone</label>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-sm text-gray-900 dark:text-white">{{ $pass->phone }}</p>
                                <a href="tel:{{ $pass->phone }}" class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</label>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-sm text-gray-900 dark:text-white">{{ $pass->email ?? 'Non renseigné' }}</p>
                                @if($pass->email)
                                    <a href="mailto:{{ $pass->email }}" class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visites</label>
                            <div class="mt-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pass->remaining_visits }}</span>
                                        <span class="text-gray-400 dark:text-gray-500">/ {{ $pass->allowed_visits }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 ml-1">restantes</span>
                                    </p>
                                    @if($pass->remaining_visits > 0 && $pass->status === 'actif')
                                        <span class="text-emerald-500 dark:text-emerald-400 text-xs">● Actif</span>
                                    @endif
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 dark:bg-blue-500 rounded-full h-2 transition-all duration-300" 
                                         style="width: {{ ($pass->remaining_visits / $pass->allowed_visits) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Période de validité</label>
                            <div class="mt-1">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        Du {{ $pass->start_date->format('d/m/Y') }} au {{ $pass->expiration_date->format('d/m/Y') }}
                                    </p>
                                </div>
                                @php
                                    $daysLeft = now()->diffInDays($pass->expiration_date, false);
                                @endphp
                                @if($daysLeft <= 7 && $daysLeft > 0 && $pass->status === 'actif')
                                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        Expire dans {{ $daysLeft }} jour(s)
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</label>
                            <div class="mt-1">
                                @php
                                    $statusConfig = [
                                        'actif' => ['color' => 'emerald', 'icon' => 'check-circle', 'text' => 'Actif'],
                                        'expiré' => ['color' => 'red', 'icon' => 'calendar-x', 'text' => 'Expiré'],
                                        'utilisé' => ['color' => 'amber', 'icon' => 'check', 'text' => 'Utilisé'],
                                        'suspendu' => ['color' => 'gray', 'icon' => 'ban', 'text' => 'Suspendu'],
                                    ];
                                    $config = $statusConfig[$pass->status] ?? ['color' => 'gray', 'icon' => 'help-circle', 'text' => ucfirst($pass->status)];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400">
                                    <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
                                    {{ $config['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code - Pleine largeur -->
                    <div class="lg:col-span-2 border-t border-gray-100 dark:border-gray-700 pt-6 mt-2">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">QR Code d'accès</h3>
                            <div class="inline-block p-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <img src="{{ $pass->getQrCodeBase64() }}" alt="QR Code pour {{ $pass->holder_name }}"
                                    class="mx-auto" style="width: 250px; height: auto;">
                            </div>
                            <div class="mt-4 flex justify-center gap-3">
                                <x-btn href="{{ $pass->getQrCodeUrl() }}" download style="outline" class="dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                                    <x-slot:prefix>
                                        <i data-lucide="download"></i>
                                    </x-slot:prefix>
                                    Télécharger QR Code
                                </x-btn>
                                <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition inline-flex items-center gap-2">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                    Imprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des scans -->
        <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Historique des scans</h2>
                    <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
                        {{ $scans->total() ?? $scans->count() }} scan(s)
                    </span>
                </div>

                @if ($scans->isEmpty())
                    <div class="text-center py-12">
                        <i data-lucide="camera-off" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Aucun scan effectué pour ce pass.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-gray-600 dark:text-gray-300">
                            <thead class="border-b border-b-gray-100 dark:border-b-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Agent</th>
                                    <th class="px-4 py-3 text-left">IP</th>
                                    <th class="px-4 py-3 text-left">Appareil</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($scans as $scan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="clock" class="w-3 h-3 text-gray-400 dark:text-gray-500"></i>
                                                <span class="text-sm text-gray-800 dark:text-white">{{ $scan->scanned_at->format('d/m/Y H:i:s') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="user" class="w-3 h-3 text-gray-400 dark:text-gray-500"></i>
                                                <span class="text-sm text-gray-800 dark:text-white">{{ $scan->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-700 dark:text-gray-300">{{ $scan->ip_address }}</code>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-1">
                                                <i data-lucide="smartphone" class="w-3 h-3 text-gray-400 dark:text-gray-500"></i>
                                                <span class="text-sm text-gray-800 dark:text-white">{{ $scan->device_info }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-gray-600 dark:text-gray-400">
                        {{ $scans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection