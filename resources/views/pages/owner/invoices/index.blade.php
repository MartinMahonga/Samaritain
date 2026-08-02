@extends('layouts.owner')

@section('title', 'Factures & Charges')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Factures & Charges</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Eau, électricité, taxes et autres charges.</p>
    </div>
    <a href="{{ route('owner.invoices.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition shrink-0">
        <i data-lucide="plus" class="w-4 h-4"></i> Ajouter une facture
    </a>
</div>

{{-- KPI --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-xl p-4 flex items-center gap-4">
        <div class="bg-red-100 dark:bg-red-900/40 rounded-xl p-3 shrink-0">
            <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalUnpaid, 0, ',', ' ') }} FCFA</p>
            <p class="text-sm text-red-500 dark:text-red-400">Total des charges impayées</p>
        </div>
    </div>
    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-xl p-4 flex items-center gap-4">
        <div class="bg-emerald-100 dark:bg-emerald-900/40 rounded-xl p-3 shrink-0">
            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</p>
            <p class="text-sm text-emerald-500 dark:text-emerald-400">Total des charges payées</p>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('owner.invoices.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-5">
    <div class="flex flex-wrap gap-3">
        <select name="property_id" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous les biens</option>
            @foreach($properties as $p)
                <option value="{{ $p->id }}" @selected(request('property_id') == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
        <select name="type" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous les types</option>
            <option value="water" @selected(request('type') === 'water')>Eau</option>
            <option value="electricity" @selected(request('type') === 'electricity')>Électricité</option>
            <option value="taxes" @selected(request('type') === 'taxes')>Taxes</option>
            <option value="garbage" @selected(request('type') === 'garbage')>Ordures</option>
            <option value="other" @selected(request('type') === 'other')>Autre</option>
        </select>
        <select name="status" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">Tous statuts</option>
            <option value="unpaid" @selected(request('status') === 'unpaid')>Impayé</option>
            <option value="paid" @selected(request('status') === 'paid')>Payé</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90 transition">Filtrer</button>
        @if(request()->hasAny(['property_id', 'type', 'status']))
            <a href="{{ route('owner.invoices.index') }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">Réinitialiser</a>
        @endif
    </div>
</form>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Bien</th>
                    <th class="px-5 py-3 font-medium text-right">Montant</th>
                    <th class="px-5 py-3 font-medium">Échéance</th>
                    <th class="px-5 py-3 font-medium text-center">Statut</th>
                    <th class="px-5 py-3 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @php
                    $typeLabels = ['water' => 'Eau', 'electricity' => 'Électricité', 'taxes' => 'Taxes', 'garbage' => 'Ordures', 'other' => 'Autre'];
                    $typeIcons = ['water' => 'droplets', 'electricity' => 'zap', 'taxes' => 'landmark', 'garbage' => 'trash-2', 'other' => 'file'];
                    $typeColors = ['water' => 'blue', 'electricity' => 'amber', 'taxes' => 'purple', 'garbage' => 'green', 'other' => 'gray'];
                @endphp
                @forelse($invoices as $invoice)
                    @php
                        $icon = $typeIcons[$invoice->type] ?? 'file';
                        $typeColor = $typeColors[$invoice->type] ?? 'gray';
                        $statusColor = $invoice->status === 'paid' ? 'emerald' : 'red';
                        $statusLabel = $invoice->status === 'paid' ? 'Payé' : 'Impayé';
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-{{ $typeColor }}-100 dark:bg-{{ $typeColor }}-900/30 rounded-lg flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $icon }}" class="w-4 h-4 text-{{ $typeColor }}-600 dark:text-{{ $typeColor }}-400"></i>
                                </div>
                                <span class="font-medium text-gray-800 dark:text-white">{{ $typeLabels[$invoice->type] ?? $invoice->type }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $invoice->property->title }}</td>
                        <td class="px-5 py-3 text-right font-bold text-gray-800 dark:text-white">{{ number_format($invoice->amount, 0, ',', ' ') }} FCFA</td>
                        <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $invoice->due_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="text-xs px-2 py-1 rounded-full bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-900/30 text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('owner.invoices.pdf', $invoice) }}" target="_blank"
                                    class="text-xs px-2 py-1 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:text-emerald-600 hover:border-emerald-200 transition"
                                    title="Télécharger PDF">
                                    <i data-lucide="file-text" class="w-3 h-3"></i>
                                </a>
                                <form action="{{ route('owner.invoices.toggle-paid', $invoice) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs px-3 py-1 rounded-lg border {{ $invoice->status === 'paid' ? 'border-red-200 dark:border-red-700 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' : 'border-emerald-200 dark:border-emerald-700 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20' }} transition">
                                        {{ $invoice->status === 'paid' ? 'Annuler' : '✓ Payé' }}
                                    </button>
                                </form>
                                <form action="{{ route('owner.invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette facture ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs px-2 py-1 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <i data-lucide="receipt" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Aucune facture enregistrée</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection
