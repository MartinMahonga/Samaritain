@extends('layouts.auth')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
    <div class="relative z-1 p-6 sm:p-0">
        <div class="flex min-h-screen w-full flex-col justify-center sm:p-0">
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">

                {{-- Icône --}}
                <div class="mb-6 flex justify-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 dark:bg-primary/20">
                        <svg class="h-8 w-8 text-primary dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                </div>

                {{-- Titre --}}
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Réinitialiser le mot de passe
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Choisissez un nouveau mot de passe sécurisé pour votre compte.
                    </p>
                </div>

                {{-- Formulaire --}}
                <div x-data="{ loading: false, showPassword: false, showConfirm: false }">
                    <form
                        method="POST"
                        action="{{ route('password.update') }}"
                        @submit="loading = true"
                    >
                        @csrf

                        {{-- Token caché --}}
                        <input type="hidden" name="token" value="{{ $request->route('token') }}" />

                        <div class="space-y-5">
                            {{-- Email --}}
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Adresse email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $request->email) }}"
                                    placeholder="Entrez votre adresse email"
                                    autofocus
                                    class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary @error('email') border-red-400 dark:border-red-500 @enderror"
                                />
                                @error('email')
                                    <span class="mt-1 block text-xs text-red-500 dark:text-red-400">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Mot de passe --}}
                            <div>
                                <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nouveau mot de passe
                                </label>
                                <div class="relative">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        id="password"
                                        name="password"
                                        placeholder="Entrez votre nouveau mot de passe"
                                        class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary @error('password') border-red-400 dark:border-red-500 @enderror"
                                    />
                                    <button
                                        type="button"
                                        x-on:click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        tabindex="-1"
                                    >
                                        <svg x-show="!showPassword" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <svg x-show="showPassword" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="mt-1 block text-xs text-red-500 dark:text-red-400">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Confirmation mot de passe --}}
                            <div>
                                <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Confirmer le mot de passe
                                </label>
                                <div class="relative">
                                    <input
                                        :type="showConfirm ? 'text' : 'password'"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Confirmez votre nouveau mot de passe"
                                        class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary @error('password_confirmation') border-red-400 dark:border-red-500 @enderror"
                                    />
                                    <button
                                        type="button"
                                        x-on:click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        tabindex="-1"
                                    >
                                        <svg x-show="!showConfirm" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <svg x-show="showConfirm" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="mt-1 block text-xs text-red-500 dark:text-red-400">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Bouton --}}
                            <button
                                id="reset-password-btn"
                                type="submit"
                                :disabled="loading"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:opacity-60 dark:bg-primary-600 dark:hover:bg-primary-700"
                            >
                                <svg
                                    x-show="loading"
                                    class="h-4 w-4 animate-spin"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span x-text="loading ? 'Réinitialisation…' : 'Réinitialiser le mot de passe'"></span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Lien retour --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                        </svg>
                        Retour à la connexion
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
