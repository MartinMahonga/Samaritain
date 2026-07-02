@php
    $user = auth()->user();
    $isLoggedIn = $user !== null;
    $isInvitedUser = $isLoggedIn && $invitation && strtolower($user->email) === strtolower($invitation->email);
    $isExpired = $invitation ? $invitation->isExpired() : false;
    $isAccepted = $invitation ? $invitation->isAccepted() : false;
    $isCancelled = $invitation ? $invitation->isCancelled() : false;
@endphp

@extends('layouts.auth')

@section('content')
    <div class="flex flex-col justify-center items-center min-h-screen px-4 py-12">
        <div class="w-full max-w-md">

            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-sm p-8">

                {{-- Icône + en-tête --}}
                <div class="flex flex-col items-center text-center mb-6">
                    <div @class([
                        'flex items-center justify-center w-14 h-14 rounded-full mb-4',
                        'bg-red-100 dark:bg-red-900/30' => $isExpired,
                        'bg-blue-100 dark:bg-blue-900/30' => $isAccepted,
                        'bg-gray-100 dark:bg-gray-700' => $isCancelled,
                        'bg-primary/10 dark:bg-primary-500/10' => !$isExpired && !$isAccepted && !$isCancelled,
                    ])>
                        @if ($isExpired)
                            <i data-lucide="clock-alert" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                        @elseif ($isAccepted)
                            <i data-lucide="check-circle-2" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                        @elseif ($isCancelled)
                            <i data-lucide="ban" class="w-6 h-6 text-gray-500 dark:text-gray-400"></i>
                        @else
                            <i data-lucide="mail-check" class="w-6 h-6 text-primary dark:text-primary-400"></i>
                        @endif
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Invitation à rejoindre l'équipe</h2>

                    @if ($invitation && $invitation->relationLoaded('role'))
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Vous avez été invité en tant que
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $invitation->role->name }}</span>
                        </p>
                    @endif
                </div>

                {{-- Erreurs --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-600/50 text-red-700 dark:text-red-400 rounded-xl text-sm">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (isset($error_message))
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-600/50 text-red-700 dark:text-red-400 rounded-xl text-sm">
                        {{ $error_message }}
                    </div>
                @endif

                {{-- États terminaux --}}
                @if ($isExpired)
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-600/50 text-red-700 dark:text-red-400 rounded-xl text-sm text-center">
                        Cette invitation a expiré. Demandez à un administrateur de vous en envoyer une nouvelle.
                    </div>
                @elseif ($isAccepted)
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-600/50 text-blue-700 dark:text-blue-400 rounded-xl text-sm text-center">
                        Cette invitation a déjà été acceptée.
                    </div>
                @elseif ($isCancelled)
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-600/50 text-gray-600 dark:text-gray-400 rounded-xl text-sm text-center">
                        Cette invitation a été annulée.
                    </div>
                @else
                    @if (! $isLoggedIn)
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-600/50 text-amber-700 dark:text-amber-400 rounded-xl text-sm text-center mb-4">
                            Vous devez être connecté pour accepter cette invitation.
                        </div>
                        <x-btn href="{{ route('login') }}" class="w-full justify-center">
                            Se connecter
                        </x-btn>

                    @elseif (! $isInvitedUser)
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-600/50 text-amber-700 dark:text-amber-400 rounded-xl text-sm text-center mb-4">
                            Vous êtes connecté en tant que <strong>{{ $user->email }}</strong>,
                            mais cette invitation est destinée à <strong>{{ $invitation->email }}</strong>.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="inline-flex items-center gap-1.5 text-sm text-red-600 dark:text-red-400 hover:underline">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Se déconnecter
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>

                    @else
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.invitations.accept', $invitation) }}" class="flex-1">
                                @csrf
                                <x-btn type="submit" class="w-full justify-center">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    Accepter
                                </x-btn>
                            </form>

                            <form method="POST" action="{{ route('admin.invitations.decline', $invitation) }}" class="flex-1"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cette invitation ?');">
                                @csrf
                                <x-btn style="destructive" type="submit" class="w-full justify-center">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                    Refuser
                                </x-btn>
                            </form>
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection