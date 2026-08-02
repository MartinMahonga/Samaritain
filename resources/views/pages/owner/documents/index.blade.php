@extends('layouts.owner')

@section('title', 'Documents')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Documents</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">
            {{ $totalDocuments }} document(s) &bull; {{ $totalSize > 0 ? number_format($totalSize / 1048576, 1) : 0 }} Mo
        </p>
    </div>
    <button x-on:click="$dispatch('open-upload-modal')"
        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition shrink-0">
        <i data-lucide="upload" class="w-4 h-4"></i> Uploader un document
    </button>
</div>

{{-- Filters & Search --}}
<form method="GET" action="{{ route('owner.documents.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-5">
    <div class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un document..."
                class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <select name="property_id" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous les biens</option>
            @foreach($properties as $p)
                <option value="{{ $p->id }}" @selected(request('property_id') == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
        <select name="category" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Toutes catégories</option>
            <option value="invoice" @selected(request('category') === 'invoice')>Factures</option>
            <option value="receipt" @selected(request('category') === 'receipt')>Reçus</option>
            <option value="quote" @selected(request('category') === 'quote')>Devis</option>
            <option value="inspection" @selected(request('category') === 'inspection')>États des lieux</option>
            <option value="other" @selected(request('category') === 'other')>Autres</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 transition">Filtrer</button>
        @if(request()->anyFilled(['search', 'property_id', 'category']))
            <a href="{{ route('owner.documents.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">Réinitialiser</a>
        @endif
    </div>
</form>

{{-- Documents Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($documents as $doc)
        @php
            $catIcons = ['invoice' => 'receipt', 'receipt' => 'check-square', 'quote' => 'file-edit', 'inspection' => 'clipboard-check', 'other' => 'paperclip'];
            $catColors = ['invoice' => 'red', 'receipt' => 'emerald', 'quote' => 'blue', 'inspection' => 'purple', 'other' => 'gray'];
            $catLabels = ['invoice' => 'Facture', 'receipt' => 'Reçu', 'quote' => 'Devis', 'inspection' => 'État des lieux', 'other' => 'Autre'];
            $icon = $catIcons[$doc->category] ?? 'paperclip';
            $color = $catColors[$doc->category] ?? 'gray';
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 hover:shadow-md transition group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 rounded-xl flex items-center justify-center">
                    <i data-lucide="{{ $icon }}" class="w-5 h-5 text-{{ $color }}-600 dark:text-{{ $color }}-400"></i>
                </div>
                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                    <a href="{{ route('owner.documents.download', $doc) }}"
                        class="p-1.5 rounded-md text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20"
                        title="Télécharger">
                        <i data-lucide="download" class="w-4 h-4"></i>
                    </a>
                    <form action="{{ route('owner.documents.destroy', $doc) }}" method="POST"
                        onsubmit="return confirm('Supprimer ce document ?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="p-1.5 rounded-md text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"
                            title="Supprimer">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
            <p class="font-medium text-gray-800 dark:text-white text-sm truncate">{{ $doc->name }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $doc->property?->title ?? 'Général' }}</p>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50 dark:border-gray-700">
                <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-600 dark:text-{{ $color }}-400">
                    {{ $catLabels[$doc->category] ?? $doc->category }}
                </span>
                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $doc->file_size ? number_format($doc->file_size / 1024, 1) : '?' }} Ko</span>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
            <i data-lucide="folder-open" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
            <p class="text-sm text-gray-400 dark:text-gray-500">Aucun document trouvé</p>
            <button x-on:click="$dispatch('open-upload-modal')"
                class="mt-2 inline-block text-xs text-primary hover:underline cursor-pointer">
                Uploader un document →
            </button>
        </div>
    @endforelse
</div>

@if($documents->hasPages())
    <div class="mt-4">{{ $documents->links() }}</div>
@endif

{{-- Upload Modal --}}
<div x-data="{ open: false }"
     x-on:open-upload-modal.window="open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    {{-- Backdrop --}}
    <div x-on:click="open = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

    {{-- Modal --}}
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-lg mx-4 p-6 z-10"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-on:click.away="open = false">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Uploader un document</h2>
            <button x-on:click="open = false" class="p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('owner.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du document</label>
                <input type="text" name="name" required
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catégorie</label>
                <select name="category" required
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="invoice">Facture</option>
                    <option value="receipt">Reçu</option>
                    <option value="quote">Devis</option>
                    <option value="inspection">État des lieux</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Propriété (optionnel)</label>
                <select name="property_id"
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Général</option>
                    @foreach($properties as $p)
                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fichier</label>
                <input type="file" name="document_file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer">
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PDF, JPG, PNG, DOC, XLS jusqu'à 10 Mo</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition">
                    Uploader
                </button>
                <button type="button" x-on:click="open = false"
                    class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
@endsection