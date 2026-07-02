@extends('layouts.dashboard')

@section('content')
    <h2 class="dark:text-white">Inviter un nouveau membre</h2>
    <x-container-dashed class="dark:border-gray-700">
        <div class="py-12">
            <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow dark:shadow-gray-900/50">
                <form method="POST" action="{{ route('admin.invitations.store') }}" class="flex flex-col gap-4">
                    @csrf
                    <x-form.input name="email" label="Adresse mail" type="email" placeholder="Entrez une adresse mail" class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />
                    <x-form.select name="role_id" label="Rôle" placeholder="Assigner un rôle" :options="$roles" class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />

                    <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">Envoyer l'invitation</x-btn>
                </form>
            </div>
        </div>
    </x-container-dashed>
@endsection