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
    <div class="flex flex-col justify-center items-center h-screen">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Invitation à rejoindre l'équipe</h2>
        @if ($invitation && $invitation->relationLoaded('role'))
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Vous avez été invité à rejoindre l'agence en tant que <strong>{{ $invitation->role->name }}</strong>.
            </p>
        @endif

        @if ($errors->any())
            <div class="mt-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 rounded-lg">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($error_message))
            <div class="mt-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 rounded-lg">
                {{ $error_message }}
            </div>
        @endif

        @if ($isExpired)
            <div class="mt-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 rounded-lg">
                Cette invitation a expiré.
            </div>
        @elseif ($isAccepted)
            <div class="mt-4 p-4 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-600 text-blue-700 dark:text-blue-400 rounded-lg">
                Cette invitation a déjà été acceptée.
            </div>
        @elseif ($isCancelled)
            <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-900/30 border border-gray-400 dark:border-gray-600 text-gray-700 dark:text-gray-400 rounded-lg">
                Cette invitation a été annulée.
            </div>
        @else
            @if (! $isLoggedIn)
                <div class="mt-4 p-4 bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-400 rounded-lg">
                    Vous devez être connecté pour accepter cette invitation.
                </div>
                <div class="mt-4 flex gap-3">
                    <x-btn href="{{ route('login') }}">
                        Se connecter
                    </x-btn>
                </div>
            @elseif (! $isInvitedUser)
                <div class="mt-4 p-4 bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-400 rounded-lg">
                    Vous êtes connecté en tant que <strong>{{ $user->email }}</strong>, mais cette invitation est destinée à <strong>{{ $invitation->email }}</strong>.
                </div>
                <div class="mt-4">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="text-red-600 dark:text-red-400 hover:underline">
                        Se déconnecter
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            @else
                <div class="mt-6 flex gap-3">
                    <form method="POST" action="{{ route('admin.invitations.accept', $invitation) }}" class="inline">
                        @csrf
                        <x-btn type="submit">
                            Accepter l'invitation
                        </x-btn>
                    </form>

                    <form method="POST" action="{{ route('admin.invitations.decline', $invitation) }}" class="inline"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cette invitation ?');">
                        @csrf
                        <x-btn style="destructive" type="submit">
                            Refuser
                        </x-btn>
                    </form>
                </div>
            @endif
        @endif
    </div>
@endsection
