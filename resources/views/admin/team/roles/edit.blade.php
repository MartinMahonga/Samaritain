@extends('layouts.dashboard')

@section('content')
    <h2 class="text-2xl font-bold dark:text-white">Modifier le rôle : {{ ucfirst($role->name) }}</h2>
    <x-container-dashed>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm dark:shadow-gray-900/50 sm:rounded-lg">
                    <div class="p-6">

                        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                            @csrf
                            @method('PUT')

                            <!-- Nom du rôle -->
                            <div class="mb-4">
                                <x-form.input label="Nom du rôle" name="name" icon="key-round" :value="$role->name" />
                            </div>

                            <!-- Permissions -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Permissions</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($permissions as $permission)
                                        <label class="flex items-center space-x-3">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400"
                                                {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <span
                                                class="text-sm text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="flex justify-end space-x-3">
                                <x-btn style="outline" href="{{ route('admin.roles.index') }}">
                                    Annuler
                                </x-btn>
                                <x-btn type="submit">
                                    Mettre à jour
                                </x-btn>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-container-dashed>
@endsection
