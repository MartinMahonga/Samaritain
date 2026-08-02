@extends('layouts.owner')

@section('title', 'États des lieux')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">États des lieux</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Entrées et sorties des locataires.</p>
    </div>
    <div class="flex gap-2 shrink-0">
        {{-- Compare Button --}}
        @if($properties->isNotEmpty())
            <button onclick="document.getElementById('compareModal').classList.remove('hidden')"
                class="flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <i data-lucide="columns-2" class="w-4 h-4"></i> Comparer
            </button>
        @endif
        <a href="{{ route('owner.inspections.create') }}"
            class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition">
            <i data-lucide="plus" class="w-4 h-4"></i> Nouvel état des lieux
        </a>
    </div>
</div>

{{-- Compare Modal --}}
<div id="compareModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-sm w-full m-4">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Comparer entrée / sortie</h3>
        <form action="{{ route('owner.inspections.compare') }}" method="GET">
            <x-form.select label="Propriété" name="property_id" icon="home"
                placeholder="Sélectionner un bien"
                :options="$properties->pluck('title', 'id')->toArray()" />
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 transition">Comparer</button>
                <button type="button" onclick="document.getElementById('compareModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">Annuler</button>
            </div>
        </form>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('owner.inspections.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-5">
    <div class="flex flex-wrap gap-3">
        <select name="property_id" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous les biens</option>
            @foreach($properties as $p)
                <option value="{{ $p->id }}" @selected(request('property_id') == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
        <select name="type" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous types</option>
            <option value="check_in" @selected(request('type') === 'check_in')>Entrée</option>
            <option value="check_out" @selected(request('type') === 'check_out')>Sortie</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 transition">Filtrer</button>
    </div>
</form>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Bien</th>
                    <th class="px-5 py-3 font-medium">Inspecteur</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($inspections as $inspection)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-5 py-3">
                            @if($inspection->type === 'check_in')
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">↓ Entrée</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">↑ Sortie</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-800 dark:text-white">{{ $inspection->property->title }}</td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $inspection->inspector_name }}</td>
                        <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $inspection->date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('owner.inspections.show', $inspection) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Voir</a>
                                <a href="{{ route('owner.inspections.pdf', $inspection) }}" class="text-gray-500 dark:text-gray-400 hover:text-primary transition">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <i data-lucide="clipboard-check" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Aucun état des lieux enregistré</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inspections->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">{{ $inspections->links() }}</div>
    @endif
</div>
@endsection
