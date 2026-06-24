@extends('layouts.auth')

@section('title', 'Vérifiez votre adresse email')

@section('content')
    <div class="relative z-1 p-6 sm:p-0">
        <div class="flex min-h-screen w-full flex-col justify-center sm:p-0">
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">

                {{-- Icône --}}
                <div class="mb-6 flex justify-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 dark:bg-primary/20">
                        <svg class="h-8 w-8 text-primary dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </div>
                </div>

                {{-- Titre --}}
                <div class="mb-6 text-center">
                    <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Vérifiez votre email
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Nous avons envoyé un lien de vérification à votre adresse email.
                        Cliquez sur ce lien pour activer votre compte.
                    </p>
                </div>

                {{-- Alerte succès --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                        <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                    </div>
                @endif

                {{-- Formulaire renvoi --}}
                <div x-data="{ loading: false }">
                    <form
                        method="POST"
                        action="{{ route('verification.send') }}"
                        @submit="loading = true"
                    >
                        @csrf
                        <button
                            id="resend-verification-btn"
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
                            <span x-text="loading ? 'Envoi en cours…' : 'Renvoyer le lien de vérification'"></span>
                        </button>
                    </form>
                </div>

                {{-- Séparateur --}}
                <div class="relative my-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-background px-4 text-gray-400 dark:bg-gray-950 dark:text-gray-500">ou</span>
                    </div>
                </div>

                {{-- Déconnexion --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        id="logout-btn"
                        type="submit"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Se déconnecter
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection
