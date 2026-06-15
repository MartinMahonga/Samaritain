@extends('layouts.base')

@section('title', 'Profil Utilisateur')

@section('content')
    <x-blade-components::layout.container>
        <div class="max-w-4xl mx-auto flex flex-col gap-6 py-4">
            <div>
                <h1 class="text-2xl font-bold text-[var(--foreground)]">Mon Profil</h1>
                <p class="text-sm text-[var(--muted-foreground)]">Gérez vos informations personnelles et vos paramètres de
                    sécurité.</p>
            </div>
    
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informations de base -->
                <div class="md:col-span-2 flex flex-col gap-6">
                    <x-card class="p-6">
                        <h2 class="text-lg font-semibold text-[var(--foreground)] mb-4">Informations personnelles</h2>
                        <x-ui.profile-info-form :user="$user" />
                    </x-card>
    
                    <x-card class="p-6">
                        <h2 class="text-lg font-semibold text-[var(--foreground)] mb-4">Sécurité</h2>
                        <x-ui.password-form />
                    </x-card>
                </div>
    
                <!-- Colonne latérale (Photo + Suppression) -->
                <div class="flex flex-col gap-6">
                    <x-card class="p-6 text-center">
                        <h2 class="text-lg font-semibold text-[var(--foreground)] mb-4 text-left">Photo de profil</h2>
                        <x-ui.profile-photo :user="$user" />
                    </x-card>
    
                    <x-card class="p-6 border-[var(--destructive)] border-opacity-50">
                        <h2 class="text-lg font-semibold text-[var(--destructive)] mb-2">Zone de danger</h2>
                        <p class="text-xs text-[var(--muted-foreground)] mb-4">
                            Une fois votre compte supprimé, toutes ses ressources et données seront effacées de manière
                            permanente.
                        </p>
                        <x-ui.delete-account />
                    </x-card>
                </div>
            </div>
        </div>
    </x-blade-components::layout.container>
@endsection
