@extends('layouts.owner')

@section('title', 'Ajouter une facture')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.invoices.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux factures
    </a>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Enregistrer une facture</h1>
</div>

<form action="{{ route('owner.invoices.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
    @csrf
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
        <x-form.select label="Propriété concernée" name="property_id" icon="home"
            placeholder="Sélectionner un bien"
            :options="$properties->pluck('title', 'id')->toArray()" />

        <x-form.select label="Type de charge" name="type" icon="tag"
            placeholder="Sélectionner un type"
            :options="['water' => 'Eau', 'electricity' => 'Électricité', 'taxes' => 'Taxes', 'garbage' => 'Ordures', 'other' => 'Autre']" />

        <x-form.input label="Montant (FCFA)" name="amount" icon="banknote" type="number" placeholder="15000" :value="old('amount')" />

        <x-form.input label="Date d'échéance" name="due_date" icon="calendar" type="date" :value="old('due_date')" />

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Joindre un fichier (optionnel)</label>
            <input type="file" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png"
                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition" />
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PDF, JPG, PNG — max 5 Mo</p>
        </div>

        @if($errors->any())
            <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-lg text-red-600 dark:text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="mt-4 flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition">
            Enregistrer la facture
        </button>
        <a href="{{ route('owner.invoices.index') }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            Annuler
        </a>
    </div>
</form>
@endsection
