@extends('layouts.dashboard')

@section('content')

    {{-- En-tête --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.roles.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary/10 dark:bg-primary-500/10">
            <i data-lucide="shield-plus" class="w-5 h-5 text-primary dark:text-primary-400"></i>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">Créer un rôle</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Définissez un nom et les permissions associées</p>
        </div>
    </div>

    <x-container-dashed>
        <div class="py-12">
            <div class="max-w-4xl mx-auto">

                <div x-data="{
                    permissions: {{ json_encode(collect($permissions)->pluck('id')) }},
                    checked: [],
                    get allChecked() { return this.permissions.length > 0 && this.permissions.every(id => this.checked.includes(id)) },
                    toggleAll() {
                        this.checked = this.allChecked ? [] : [...this.permissions];
                        this.permissions.forEach(id => {
                            document.getElementById('perm-' + id).checked = this.checked.includes(id);
                        });
                    },
                    toggle(id) {
                        this.checked.includes(id) ? this.checked = this.checked.filter(x => x !== id) : this.checked.push(id);
                    }
                }" class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm dark:shadow-gray-900/50">

                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf

                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <x-form.input
                                label="Nom du rôle"
                                name="name"
                                icon="tag"
                                required
                                placeholder="Ex : Gestionnaire"
                                class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-primary-500" />
                        </div>

                        {{-- Permissions --}}
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Permissions
                                    <span class="ml-1 text-xs font-normal text-gray-400 dark:text-gray-500"
                                        x-text="'(' + checked.length + '/' + permissions.length + ' sélectionnées)'"></span>
                                </label>
                                <button type="button" @click="toggleAll()"
                                    class="text-sm font-medium text-primary dark:text-primary-400 hover:underline"
                                    x-text="allChecked ? 'Tout désélectionner' : 'Tout sélectionner'">
                                </button>
                            </div>

                            @if ($permissions->isEmpty())
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Aucune permission disponible.</p>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach ($permissions as $perm)
                                        <label
                                            :class="checked.includes({{ $perm->id }})
                                                ? 'border-primary/40 bg-primary/5 dark:bg-primary-500/10'
                                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
                                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg border cursor-pointer transition-colors">
                                            <input type="checkbox" id="perm-{{ $perm->id }}" name="permissions[]"
                                                value="{{ $perm->id }}"
                                                @change="toggle({{ $perm->id }})"
                                                class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-primary dark:text-primary-400 focus:ring-primary dark:focus:ring-primary-400">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ ucfirst(str_replace('-', ' ', $perm->name)) }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Boutons --}}
                        <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 rounded-b-xl">
                            <x-btn style="outline" href="{{ route('admin.roles.index') }}">
                                Annuler
                            </x-btn>
                            <x-btn type="submit" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                Créer
                            </x-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-container-dashed>
@endsection