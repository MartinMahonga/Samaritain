@extends('layouts.owner')

@section('title', 'Nouveau virement Mobile Money')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.payouts.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux virements
    </a>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Nouveau virement Mobile Money</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Envoyez des fonds via pawaPay (Airtel Money / MTN MoMo).</p>
</div>

<div class="max-w-lg">
    {{-- Info banner --}}
    <div class="mb-5 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl text-sm text-blue-700 dark:text-blue-300 flex items-start gap-2">
        <i data-lucide="info" class="w-4 h-4 mt-0.5 shrink-0"></i>
        <p>Le virement est asynchrone — <strong>pawaPay confirme le statut final</strong> en quelques secondes à quelques minutes via callback. Consultez la liste des virements pour suivre l'état.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach($errors->all() as $error)
                    <li class="flex items-center gap-1">
                        <i data-lucide="x-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.payouts.store') }}" method="POST"
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-5">
        @csrf

        {{-- Phone number --}}
        <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                Numéro Mobile Money <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="smartphone" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input
                    type="text"
                    id="phone_number"
                    name="phone_number"
                    value="{{ old('phone_number') }}"
                    placeholder="Ex : 242065000000"
                    autocomplete="off"
                    class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('phone_number') border-red-400 dark:border-red-500 @else border-gray-200 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                    required
                >
            </div>
            @error('phone_number')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Numéro au format international (ex : 242XXXXXXXXX pour le Congo).</p>
        </div>

        {{-- Amount --}}
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                Montant (FCFA) <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="banknote" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input
                    type="number"
                    id="amount"
                    name="amount"
                    value="{{ old('amount') }}"
                    placeholder="Ex : 50000"
                    min="100"
                    step="1"
                    class="w-full pl-9 pr-4 py-2.5 rounded-lg border @error('amount') border-red-400 dark:border-red-500 @else border-gray-200 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                    required
                >
            </div>
            @error('amount')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Minimum 100 FCFA. Aucune décimale.</p>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                Description <span class="text-gray-400 text-xs font-normal">(optionnel)</span>
            </label>
            <textarea
                id="description"
                name="description"
                rows="2"
                placeholder="Ex : Remboursement caution loyer août 2026"
                class="w-full px-4 py-2.5 rounded-lg border @error('description') border-red-400 dark:border-red-500 @else border-gray-200 dark:border-gray-600 @enderror bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary transition resize-none"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Provider notice --}}
        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-xs text-amber-700 dark:text-amber-300 flex items-start gap-2">
            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mt-0.5 shrink-0"></i>
            <span>L'opérateur (Airtel / MTN) est <strong>détecté automatiquement</strong> à partir du numéro. Ne forcez pas un opérateur manuellement.</span>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('owner.payouts.index') }}"
                class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Annuler
            </a>
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-lg text-sm font-medium transition">
                <i data-lucide="send" class="w-4 h-4"></i>
                Envoyer le virement
            </button>
        </div>
    </form>
</div>
@endsection
