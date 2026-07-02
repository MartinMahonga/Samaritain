@extends('layouts.dashboard')

@section('content')

    {{-- En-tête --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.members.show', $member) }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary/10 dark:bg-primary-500/10 text-primary dark:text-primary-400 font-semibold shrink-0">
            {{ strtoupper(substr($member->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">Modifier {{ $member->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Informations, rôle et statut du compte</p>
        </div>
    </div>

    <x-container-dashed>
        <div class="py-12">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm dark:shadow-gray-900/50 overflow-hidden">

                    <form method="POST" action="{{ route('admin.members.update', $member) }}">
                        @csrf
                        @method('PUT')

                        <div class="p-6 space-y-5">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <x-form.input
                                    label="Nom"
                                    name="name"
                                    icon="user"
                                    :value="old('name', $member->name)"
                                    class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />

                                <x-form.input
                                    label="Email"
                                    name="email"
                                    type="email"
                                    icon="mail"
                                    :value="old('email', $member->email)"
                                    class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />
                            </div>

                            <x-form.select
                                label="Rôle"
                                name="role_id"
                                icon="shield"
                                :options="$roles"
                                :selected="$member->roles->first()->id ?? null"
                                class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />

                            {{-- Statut actif --}}
                            <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Compte actif</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Un compte inactif ne peut pas se connecter</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $member->is_active) ? 'checked' : '' }}
                                        class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 rounded-full peer peer-checked:bg-primary dark:peer-checked:bg-primary-500 transition-colors"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                                </label>
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
                            <x-btn style="outline" href="{{ route('admin.members.show', $member) }}">
                                Annuler
                            </x-btn>
                            <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Mettre à jour
                            </x-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-container-dashed>
@endsection