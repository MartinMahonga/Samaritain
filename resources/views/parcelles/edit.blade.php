{{-- resources/views/parcelles/edit.blade.php --}}

@extends('layouts.base')

@section('title', 'Modifier la parcelle')

@section('content')
    <x-blade-components::layout.container>
        <div class="max-w-3xl mx-auto py-8">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('parcelles.show', $parcelle) }}" class="p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Modifier la parcelle</h1>
                    <p class="text-sm text-gray-500 mt-1">Modifiez les informations de la parcelle</p>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('parcelles.update', $parcelle) }}" method="POST" enctype="multipart/form-data" x-data="parcelleForm()"
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-gray-700">Titre <span class="text-red-500">*</span></label>
                    <input type="text" name="titre" value="{{ old('titre', $parcelle->titre) }}" placeholder="Ex: Grande parcelle résidentielle"
                        class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-gray-700">Description</label>
                    <textarea name="description" rows="3" placeholder="Décrivez la parcelle..."
                        class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('description', $parcelle->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-gray-700">Ville <span class="text-red-500">*</span></label>
                        <input type="text" name="ville" value="{{ old('ville', $parcelle->ville) }}" placeholder="Ex: Brazzaville"
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-gray-700">Quartier <span class="text-red-500">*</span></label>
                        <input type="text" name="quartier" value="{{ old('quartier', $parcelle->quartier) }}" placeholder="Ex: Bacongo"
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-gray-700">Localisation <span class="text-red-500">*</span></label>
                        <input type="text" name="localisation" value="{{ old('localisation', $parcelle->localisation) }}" placeholder="Ex: Nord de Bacongo"
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-gray-700">Superficie (m²) <span class="text-red-500">*</span></label>
                        <input type="number" name="superficie" value="{{ old('superficie', $parcelle->superficie) }}" min="1"
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-gray-700">Prix (FCFA) <span class="text-red-500">*</span></label>
                        <input type="number" name="prix" value="{{ old('prix', $parcelle->prix) }}" min="0"
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-gray-700">Statut</label>
                        <select name="statut"
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="disponible" {{ old('statut', $parcelle->statut) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="vendu" {{ old('statut', $parcelle->statut) === 'vendu' ? 'selected' : '' }}>Vendu</option>
                            <option value="réservé" {{ old('statut', $parcelle->statut) === 'réservé' ? 'selected' : '' }}>Réservé</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold text-gray-700">Titre foncier</label>
                        <input type="text" name="titre_foncier" value="{{ old('titre_foncier', $parcelle->titre_foncier) }}"
                            placeholder="Ex: TF-12345"
                            class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <input type="checkbox" name="viabilisee" value="1" {{ old('viabilisee', $parcelle->viabilisee) ? 'checked' : '' }}
                            class="h-4 w-4 text-emerald-600 border-gray-300 rounded" />
                        Parcelle viabilisée (eau, électricité...)
                    </label>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-gray-700">Images existantes</label>
                    @if ($parcelle->images->isEmpty())
                        <p class="text-sm text-gray-500">Aucune image enregistrée.</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($parcelle->images as $image)
                                <div class="relative group rounded-2xl overflow-hidden border border-gray-200">
                                    <img src="{{ $image->url }}" alt="Image {{ $loop->iteration }}" class="w-full h-32 object-cover" />
                                    <div class="absolute inset-x-0 bottom-0 p-2 bg-black/30 text-white text-xs flex items-center justify-between">
                                        @if ($image->principale)
                                            <span class="bg-emerald-500 px-2 py-1 rounded-full">Principale</span>
                                        @endif
                                        <form action="{{ route('parcelles.images.destroy', $image) }}" method="POST" onsubmit="return confirm('Supprimer cette image ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 rounded-full bg-red-600/90 hover:bg-red-700">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-gray-700">Ajouter des images</label>
                    <input type="file" name="images[]" x-ref="fileInput" multiple accept="image/*" class="hidden"
                        @change="handleFiles($event)" />

                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-emerald-400 transition-colors"
                        @click="$refs.fileInput.click()" @dragover.prevent @drop.prevent="handleDrop($event)">
                        <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-500">Cliquez ou glissez vos images ici</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP — max 5MB par image</p>
                    </div>

                    <div x-show="previews.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-2">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="preview" class="w-full h-24 object-cover rounded-xl border border-gray-200" />
                                <button type="button" @click="removeImage(index)"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors duration-200">
                        Enregistrer
                    </button>
                    <a href="{{ route('parcelles.show', $parcelle) }}"
                        class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Annuler</a>
                </div>
            </form>
        </div>
    </x-blade-components::layout.container>

    <script>
        function parcelleForm() {
            return {
                previews: [],

                handleFiles(event) {
                    const files = Array.from(event.target.files)
                    this.ajouterFichiers(files)
                },

                handleDrop(event) {
                    const files = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/'))
                    this.ajouterFichiers(files)
                },

                ajouterFichiers(files) {
                    files.forEach(file => {
                        const reader = new FileReader()
                        reader.onload = (e) => this.previews.push(e.target.result)
                        reader.readAsDataURL(file)
                    })
                },

                removeImage(index) {
                    this.previews.splice(index, 1)
                },
            }
        }
    </script>
@endsection
