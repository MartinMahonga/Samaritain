@extends('layouts.dashboard')

@section('content')
    @if (!$properties->isEmpty())
        <div class="flex justify-between">
            <h1>Liste des biens</h1>
            <x-btn href="{{ route('admin.property.create') }}">
                <x-slot:prefix>
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Créer un bien
            </x-btn>
        </div>
        <x-container-dashed>
            <div x-data="deleteModal()" @keydown.escape="closeModal()">
                <div class="overflow-x-auto bg-sidebar rounded-lg shadow-sm">
                    <table class="w-full text-xs text-gray-600">
                        <thead class="border-b border-b-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left">ID</th>
                                <th class="px-4 py-3 text-left">Titre</th>
                                <th class="px-4 py-3 text-left">Surface (m²)</th>
                                <th class="px-4 py-3 text-left">Prix</th>
                                <th class="px-4 py-3 text-left">Pièces</th>
                                <th class="px-4 py-3 text-left">Chambres</th>
                                <th class="px-4 py-3 text-left">Étages</th>
                                <th class="px-4 py-3 text-left">Adresse</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
    
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($properties as $property)
                                <tr>
                                    <td class="px-4 py-3">#{{ $property->id }}</td>
                                    <td class="px-4 py-3">{{ $property->title }}</td>
                                    <td class="px-4 py-3">{{ $property->surface }}</td>
                                    <td class="px-4 py-3">{{ number_format($property->price, 0, ',', ' ') }}</td>
                                    <td class="px-4 py-3">{{ $property->rooms }}</td>
                                    <td class="px-4 py-3">{{ $property->bedrooms }}</td>
                                    <td class="px-4 py-3">{{ $property->floor }}</td>
                                    <td class="px-4 py-3">{{ $property->address }}</td>
                                    <td class="px-4 py-3">
                                        @switch($property->status->value)
                                            @case('available')
                                                <span
                                                    class="px-2 py-1 text-xs font-medium text-green-500 bg-green-300 rounded-full">disponible</span>
                                            @break
    
                                            @case('sold')
                                                <span
                                                    class="px-2 py-1 text-xs font-medium text-red-500 bg-red-300 rounded-full">vendu</span>
                                            @break
    
                                            @default
                                                <span
                                                    class="px-2 py-1 text-xs font-medium text-blue-500 bg-blue-300 rounded-full">loué</span>
                                        @endswitch
                                    </td>
    
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.property.edit', $property) }}" class="block text-blue-500">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <button @click="openModal('{{ route('admin.property.destroy', $property) }}', '{{ $property->title }}')" class="block text-destructive">
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
                    {{ $properties->links() }}
                </div>
                
                <!-- Modal de confirmation de suppression -->
                <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeModal()">
                    <div class="relative w-full max-w-md rounded-lg bg-background p-6 shadow-lg" @click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                                <i data-lucide="alert-octagon" class="h-6 w-6 text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Supprimer le bien</h3>
                                <p class="mt-2 text-sm text-gray-600">
                                    Êtes-vous sûr de vouloir supprimer <strong x-text="propertyTitle"></strong> ? Cette action est irréversible.
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
            <div></div>
            <x-btn href="{{ route('admin.property.create') }}">
                <x-slot:prefix>
                    <i data-lucide="plus"></i>
                </x-slot:prefix>
                Créer le premier bien
            </x-btn>
        </div>
        <x-empty title="Aucun bien trouvé" description="Créer un premier bien pour commencer">
            <x-slot:icon>
                <i data-lucide="building"></i>
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
