@extends('layouts.dashboard')

@section('content')
    <h2 class="dark:text-white">Créer un rôle</h2>
    <x-container-dashed>
        <div class="py-12">
            <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow dark:shadow-gray-900/50">
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf
                    <div class="mb-4">
                        <x-form.input label='Nom du rôle' name='name' required class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />
                    </div>
                    <div class="mb-4">
                        <label class="dark:text-gray-300">Permissions</label><br>
                        @foreach ($permissions as $perm)
                            <label class="dark:text-gray-300">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-primary-600">
                                {{ $perm->name }}
                            </label><br>
                        @endforeach
                    </div>
                    <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">Créer</x-btn>
                </form>
            </div>
        </div>
    </x-container-dashed>
@endsection