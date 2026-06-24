@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')
    <div class="relative z-1 p-6 sm:p-0">
        <div class="flex min-h-screen w-full flex-col justify-center sm:p-0">
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">

                {{-- Icône --}}
                <div class="mb-6 flex justify-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 dark:bg-primary/20">
                        <svg class="h-8 w-8 text-primary dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                </div>

                {{-- Titre --}}
                <div class="mb-6 sm:mb-8">
                    <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Mot de passe oublié ?
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </p>
                </div>

                {{-- Message succès --}}
                @if (session('status'))
                    <div class="mb-5 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        {{ __($status ?? session('status')) }}
                    </div>
                @endif

                {{-- Formulaire --}}
                <div x-data="{ loading: false }">
                    <form
                        method="POST"
                        action="{{ route('password.email') }}"
                        @submit="loading = true"
                    >
                        @csrf
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
                                    value="{{ old('email') }}"
                                    placeholder="Entrez votre adresse email"
                                    autofocus
                                    class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary @error('email') border-red-400 dark:border-red-500 @enderror"
                                />
                                @error('email')
                                    <span class="mt-1 block text-xs text-red-500 dark:text-red-400">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Bouton --}}
                            <button
                                id="send-reset-link-btn"
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
                                <span x-text="loading ? 'Envoi en cours…' : 'Envoyer le lien de réinitialisation'"></span>
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
