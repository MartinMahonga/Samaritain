@extends('layouts.owner')

@section('title', 'Nouvel état des lieux')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.inspections.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary flex items-center gap-1 mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour aux états des lieux
    </a>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Nouvel état des lieux</h1>
</div>

<form action="{{ route('owner.inspections.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="inspectionForm()">
    @csrf

    <div class="lg:col-span-2 space-y-5">
        {{-- General Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-primary"></i> Informations générales
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.select label="Bien" name="property_id" icon="home"
                    placeholder="Sélectionner un bien"
                    :options="$properties->pluck('title', 'id')->toArray()" />
                @if($contracts->isNotEmpty())
                    <x-form.select label="Contrat lié (optionnel)" name="contract_id" icon="file-text"
                        placeholder="Aucun contrat"
                        :options="$contracts->mapWithKeys(fn($c) => [$c->id => $c->tenant_name . ' — ' . $c->property->title])->toArray()" />
                @endif
                <x-form.select label="Type" name="type" icon="arrow-right-left"
                    placeholder="Type d'état des lieux"
                    :options="['check_in' => '↓ Entrée', 'check_out' => '↑ Sortie']" />
                <x-form.input label="Date" name="date" icon="calendar" type="date" :value="old('date', today()->format('Y-m-d'))" />
                <x-form.input label="Nom de l'inspecteur" name="inspector_name" icon="user"
                    placeholder="Nom de la personne effectuant l'état" :value="old('inspector_name', auth()->user()->name)" />
            </div>
        </div>

        {{-- Room-by-Room Inspection --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                    <i data-lucide="layout" class="w-4 h-4 text-primary"></i> Pièces & Éléments
                </h3>
                <button type="button" x-on:click="addRoom()" class="flex items-center gap-1 text-xs text-primary hover:underline">
                    <i data-lucide="plus" class="w-3 h-3"></i> Ajouter une pièce
                </button>
            </div>

            <template x-for="(room, idx) in rooms" :key="idx">
                <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-3">
                        <input type="text" x-model="room.name" :name="`rooms_data[${idx}][name]`"
                            placeholder="Nom de la pièce (ex: Salon)"
                            class="text-sm font-medium bg-transparent border-b border-gray-200 dark:border-gray-600 text-gray-800 dark:text-white focus:outline-none focus:border-primary flex-1 mr-2 pb-1">
                        <button type="button" x-on:click="rooms.splice(idx, 1)" class="text-red-400 hover:text-red-600 transition">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <div class="sm:col-span-1">
                            <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">État général</label>
                            <select x-model="room.status" :name="`rooms_data[${idx}][status]`"
                                class="w-full text-sm px-2 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary">
                                <option value="good">✅ Bon état</option>
                                <option value="average">⚠️ État moyen</option>
                                <option value="damaged">❌ Endommagé</option>
                                <option value="new">🆕 Neuf</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Observations</label>
                            <input type="text" x-model="room.notes" :name="`rooms_data[${idx}][notes]`"
                                placeholder="Notes spécifiques..."
                                class="w-full text-sm px-2 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" x-on:click="addRoom()"
                class="w-full mt-2 py-2 border border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-400 dark:text-gray-500 hover:border-primary hover:text-primary transition">
                + Ajouter une pièce
            </button>
        </div>

        {{-- Notes & Photos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
            <x-form.textarea label="Observations générales" name="notes"
                placeholder="Remarques générales sur l'état du bien..." />
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Photos</label>
                <input type="file" name="photos[]" accept="image/*" multiple
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition" />
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i data-lucide="pen-line" class="w-4 h-4 text-primary"></i> Signatures
            </h3>
            <x-form.input label="Signature du locataire" name="tenant_signature" icon="user"
                placeholder="Prénom Nom du locataire" :value="old('tenant_signature')" />
            <x-form.input label="Signature du propriétaire" name="owner_signature" icon="user-check"
                placeholder="Prénom Nom du propriétaire" :value="old('owner_signature', auth()->user()->name)" />
        </div>

        @if($errors->any())
            <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-xl text-red-600 dark:text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition">
            <i data-lucide="save" class="w-4 h-4"></i>
            Enregistrer l'état des lieux
        </button>
    </div>
</form>

@push('scripts')
<script>
function inspectionForm() {
    return {
        rooms: [
            { name: 'Salon', status: 'good', notes: '' },
            { name: 'Cuisine', status: 'good', notes: '' },
            { name: 'Chambre', status: 'good', notes: '' },
            { name: 'Salle de bain', status: 'good', notes: '' },
        ],
        addRoom() {
            this.rooms.push({ name: '', status: 'good', notes: '' });
        }
    }
}
</script>
@endpush
@endsection
