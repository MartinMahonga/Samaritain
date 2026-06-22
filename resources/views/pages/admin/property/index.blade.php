@extends('layouts.dashboard')

@section('title', 'Les biens')

@section('content')
    @if (!$properties->isEmpty())
        <div class="flex justify-between">
            <h1 class="text-gray-800 dark:text-white">Liste des biens</h1>
            <x-btn href="{{ route('admin.property.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Créer un bien
            </x-btn>
        </div>
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
                                        <a href="{{ route('admin.property.show', $property) }}" class="text-gray-800 dark:text-white">{{ $property->title }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-800 dark:text-white">{{ number_format($property->price, 0, ',', ' ') }}</td>
                                    <td class="px-4 py-3">{{ $property->rooms }}</td>
                                    <td class="px-4 py-3">{{ $property->city->name }}</td>
                                    <td class="px-4 py-3">
                                        @switch($property->status->value)
                                            @case('available')
                                                <span class="px-2 py-1 text-xs font-medium text-green-500 bg-green-300 dark:bg-green-900/30 dark:text-green-400 rounded-full">disponible</span>
                                            @break
                                            @case('sold')
                                                <span class="px-2 py-1 text-xs font-medium text-red-500 bg-red-300 dark:bg-red-900/30 dark:text-red-400 rounded-full">vendu</span>
                                            @break
                                            @default
                                                <span class="px-2 py-1 text-xs font-medium text-blue-500 bg-blue-300 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">loué</span>
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($property->is_verify)
                                            <span class="px-2 py-1 text-xs font-medium text-green-500 bg-green-300 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                                <i data-lucide="check-circle" class="inline w-3 h-3"></i> Oui
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-yellow-500 bg-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full">
                                                <i data-lucide="clock" class="inline w-3 h-3"></i> Non
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($property->is_active)
                                            <span class="px-2 py-1 text-xs font-medium text-green-500 bg-green-300 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                                <i data-lucide="eye" class="inline w-3 h-3"></i> Actif
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-red-500 bg-red-300 dark:bg-red-900/30 dark:text-red-400 rounded-full">
                                                <i data-lucide="eye-off" class="inline w-3 h-3"></i> Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.property.show', $property) }}" class="block text-blue-500 dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('admin.property.edit', $property) }}" class="block text-yellow-500 dark:text-yellow-400 hover:text-yellow-600 dark:hover:text-yellow-300">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <button @click="openModal('{{ route('admin.property.destroy', $property) }}', '{{ $property->title }}')" class="block text-destructive dark:text-red-400 hover:text-red-600 dark:hover:text-red-300">
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
                <div x-cloak x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/70" @click.self="closeModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background dark:bg-gray-800 p-6 shadow-lg" @click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Supprimer le bien</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Êtes-vous sûr de vouloir supprimer <strong x-text="propertyTitle" class="text-gray-800 dark:text-white"></strong> ? Cette action est irréversible.
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
            <div></div>
            <x-btn href="{{ route('admin.property.create') }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                <x-slot:prefix>
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Créer le premier bien
            </x-btn>
        </div>
        <x-empty title="Aucun bien trouvé" description="Créer un premier bien pour commencer" class="dark:text-gray-400">
            <x-slot:icon>
                <i data-lucide="building" class="text-gray-400 dark:text-gray-500"></i>
            </x-slot:icon>
        </x-empty>
    @endif

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
@endsection