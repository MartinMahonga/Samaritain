@extends('layouts.dashboard')

@section('content')

    {{-- En-tête --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.members.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">Détails du membre</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Informations et statut du compte</p>
        </div>
    </div>

    <x-container-dashed>
        <div class="py-12">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm dark:shadow-gray-900/50 overflow-hidden">

                    {{-- Bandeau profil --}}
                    <div class="flex items-center gap-4 p-6 bg-gray-50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 dark:bg-primary-500/10 text-primary dark:text-primary-400 text-xl font-semibold shrink-0">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $member->name }}</h3>
                                @if ($member->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Inactif
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $member->email }}</p>
                        </div>
                    </div>

                    {{-- Détails --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100 dark:divide-gray-700">

                        <div class="flex items-start gap-3 p-6">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 shrink-0">
                                <i data-lucide="mail" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Email</p>
                                <p class="text-sm text-gray-800 dark:text-gray-200 break-all">{{ $member->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-6">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 shrink-0">
                                <i data-lucide="shield" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Rôle</p>
                                <p class="text-sm text-gray-800 dark:text-gray-200">{{ $member->roles->first()->name ?? 'Aucun' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-6">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 shrink-0">
                                <i data-lucide="calendar" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Membre depuis</p>
                                <p class="text-sm text-gray-800 dark:text-gray-200">{{ $member->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-6">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-700 shrink-0">
                                <i data-lucide="activity" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-0.5">Statut du compte</p>
                                <p class="text-sm text-gray-800 dark:text-gray-200">{{ $member->is_active ? 'Actif' : 'Inactif' }}</p>
                            </div>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
                        <x-btn style="outline" href="{{ route('admin.members.index') }}">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Retour
                        </x-btn>
                    </div>

                </div>
            </div>
        </div>
    </x-container-dashed>
@endsection