@extends('layouts.dashboard')

@section('content')

    {{-- En-tête --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10 dark:bg-primary-500/10">
            <i data-lucide="user-round-plus" class="w-5 h-5 text-primary dark:text-primary-400"></i>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">Inviter un nouveau membre</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Il recevra un email pour rejoindre l'équipe</p>
        </div>
    </div>

    <x-container-dashed class="dark:border-gray-700">
        <div class="py-12">
            <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-8 rounded-xl shadow-sm dark:shadow-gray-900/50">

                <form method="POST" action="{{ route('admin.invitations.store') }}" class="flex flex-col gap-5">
                    @csrf

                    <x-form.input
                        name="email"
                        label="Adresse mail"
                        type="email"
                        placeholder="nom@exemple.com"
                        class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />

                    <x-form.select
                        name="role_id"
                        label="Rôle"
                        placeholder="Assigner un rôle"
                        :options="$roles"
                        class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />

                    <div class="flex items-center justify-end gap-3 pt-2 mt-2 border-t border-gray-100 dark:border-gray-700">
                        <x-btn style="outline" href="{{ route('admin.invitations.index') }}">
                            Annuler
                        </x-btn>
                        <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Envoyer l'invitation
                        </x-btn>
                    </div>
                </form>
            </div>
        </div>
    </x-container-dashed>
@endsection