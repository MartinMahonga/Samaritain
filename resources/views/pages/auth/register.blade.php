@extends('layouts.auth')

@section('title', 'Créer un compte')

@section('content')
    <div class="relative z-1 p-6 sm:p-0">
        <div class="flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row">
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                <div class="mb-5 sm:mb-8">
                    <h1 class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90">
                        Créer un compte
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Rejoignez-nous dès maintenant et accédez à tous nos services !
                    </p>
                </div>
                <div>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-5">
                        <x-btn href="{{ route('auth.redirect', 'google') }}" style="outline" class="md:text-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.7511 10.1944C18.7511 9.47495 18.6915 8.94995 18.5626 8.40552H10.1797V11.6527H15.1003C15.0011 12.4597 14.4654 13.675 13.2749 14.4916L13.2582 14.6003L15.9087 16.6126L16.0924 16.6305C17.7788 15.1041 18.7511 12.8583 18.7511 10.1944Z"
                                    fill="#4285F4" />
                                <path
                                    d="M10.1788 18.75C12.5895 18.75 14.6133 17.9722 16.0915 16.6305L13.274 14.4916C12.5201 15.0068 11.5081 15.3666 10.1788 15.3666C7.81773 15.3666 5.81379 13.8402 5.09944 11.7305L4.99473 11.7392L2.23868 13.8295L2.20264 13.9277C3.67087 16.786 6.68674 18.75 10.1788 18.75Z"
                                    fill="#34A853" />
                                <path
                                    d="M5.10014 11.7305C4.91165 11.186 4.80257 10.6027 4.80257 9.99992C4.80257 9.3971 4.91165 8.81379 5.09022 8.26935L5.08523 8.1534L2.29464 6.02954L2.20333 6.0721C1.5982 7.25823 1.25098 8.5902 1.25098 9.99992C1.25098 11.4096 1.5982 12.7415 2.20333 13.9277L5.10014 11.7305Z"
                                    fill="#FBBC05" />
                                <path
                                    d="M10.1789 4.63331C11.8554 4.63331 12.9864 5.34303 13.6312 5.93612L16.1511 3.525C14.6035 2.11528 12.5895 1.25 10.1789 1.25C6.68676 1.25 3.67088 3.21387 2.20264 6.07218L5.08953 8.26943C5.81381 6.15972 7.81776 4.63331 10.1789 4.63331Z"
                                    fill="#EB4335" />
                            </svg>
                            Se connecter avec Google
                        </x-btn>
                        <x-btn href="{{ route('auth.redirect', 'facebook') }}" style="outline" class="md:text-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
                            <svg width="21" class="fill-current" height="24" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="rgb(14, 136, 232)"
                                    d="M576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320C64 440 146.7 540.8 258.2 568.5L258.2 398.2L205.4 398.2L205.4 320L258.2 320L258.2 286.3C258.2 199.2 297.6 158.8 383.2 158.8C399.4 158.8 427.4 162 438.9 165.2L438.9 236C432.9 235.4 422.4 235 409.3 235C367.3 235 351.1 250.9 351.1 292.2L351.1 320L434.7 320L420.3 398.2L351 398.2L351 574.1C477.8 558.8 576 450.9 576 320z" />
                            </svg>

                            Se connecter avec Facebook
                        </x-btn>
                    </div>
                    <div class="relative py-3 sm:py-5">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-background p-2 text-primary sm:px-5 sm:py-2 dark:text-primary-400">Ou</span>
                        </div>
                    </div>
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nom
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Entrez votre nom"
                                    class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary" />
                                @error('name')
                                    <span class="text-red-400 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Adresse mail
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Entrez votre adresse mail"
                                    class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary" />
                                @error('email')
                                    <span class="text-red-400 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Password -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Mot de passe
                                </label>
                                <input type="password" name="password" placeholder="Entrez votre mot de passe"
                                    class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary" />

                                @error('password')
                                    <span class="text-red-400 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Confirmer mot de passe
                                </label>
                                <input type="password" name="password_confirmation"
                                    placeholder="Confirmer votre mot de passe"
                                    class="shadow-theme-xs focus:border-primary focus:ring-primary/10 h-9 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary" />
                                @error('password_confirmation')
                                    <span class="text-red-400 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Button -->
                            <div>
                                <x-btn type="submit" class="w-full dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                                    Créer un compte
                                </x-btn>
                            </div>
                    </form>
                    <div class="mt-5">
                        <p class="text-center text-sm font-normal text-gray-700 sm:text-start dark:text-gray-400">
                            Vous avez déjà un compte?
                            <a href="{{ route('login') }}" class="text-primary underline hover:text-primary dark:text-primary-400 dark:hover:text-primary-300">
                                Se connecter
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection