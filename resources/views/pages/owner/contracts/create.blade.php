@extends('layouts.owner')

@section('title', 'Nouveau contrat')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.contracts.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux contrats
    </a>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Nouveau contrat de bail</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Remplissez les informations du locataire et du bien.</p>
</div>

<form action="{{ route('owner.contracts.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf

    <div class="lg:col-span-2 space-y-6">
        {{-- Tenant Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-primary"></i> Informations du locataire
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input label="Nom complet du locataire" name="tenant_name" icon="user"
                    placeholder="Jean Dupont" :value="old('tenant_name')" />
                <x-form.input label="Téléphone" name="tenant_phone" icon="phone"
                    placeholder="06 800 00 00" :value="old('tenant_phone')" />
                <x-form.input label="Email" name="tenant_email" icon="mail" type="email"
                    placeholder="locataire@email.com" :value="old('tenant_email')" />
            </div>
        </div>

        {{-- Contract Terms --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-primary"></i> Conditions du bail
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input label="Date de début" name="start_date" icon="calendar"
                    type="date" :value="old('start_date')" />
                <x-form.input label="Date de fin (optionnel)" name="end_date" icon="calendar"
                    type="date" :value="old('end_date')" />
                <x-form.input label="Loyer mensuel (FCFA)" name="monthly_rent" icon="banknote"
                    type="number" placeholder="150000" :value="old('monthly_rent')" />
                <x-form.input label="Dépôt de garantie (FCFA)" name="deposit" icon="shield"
                    type="number" placeholder="300000" :value="old('deposit')" />
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4 text-primary"></i> Bien concerné
            </h3>
            <x-form.select label="Propriété" name="property_id" icon="home"
                placeholder="Sélectionner un bien"
                :options="$properties->pluck('title', 'id')->toArray()" />

            <div class="mt-4">
                <x-form.select label="Statut du contrat" name="status" icon="badge-check"
                    placeholder="Statut"
                    :options="['active' => 'Actif', 'pending' => 'En attente', 'terminated' => 'Résilié']" />
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4 text-sm text-blue-700 dark:text-blue-300">
            <div class="flex gap-2">
                <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <p>Un échéancier de 12 mois de loyer sera automatiquement généré lors de la création du contrat.</p>
            </div>
        </div>

        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition">
            <i data-lucide="save" class="w-4 h-4"></i>
            Créer le contrat
        </button>
    </div>
</form>
@endsection
