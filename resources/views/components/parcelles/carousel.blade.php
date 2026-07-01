{{-- resources/views/components/parcelles/carousel.blade.php --}}

@php $filters = $filters ?? []; @endphp

<div class="w-full">
    <form action="{{ route('parcelles.index') }}" method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Ville</label>
                <input type="text" name="ville" value="{{ old('ville', $filters['ville'] ?? '') }}" placeholder="Ex: Brazzaville"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Quartier</label>
                <input type="text" name="quartier" value="{{ old('quartier', $filters['quartier'] ?? '') }}" placeholder="Ex: Bacongo"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Statut</label>
                <select name="statut" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Tous</option>
                    <option value="disponible" {{ old('statut', $filters['statut'] ?? '') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="vendu" {{ old('statut', $filters['statut'] ?? '') === 'vendu' ? 'selected' : '' }}>Vendu</option>
                    <option value="réservé" {{ old('statut', $filters['statut'] ?? '') === 'réservé' ? 'selected' : '' }}>Réservé</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Viabilisée</label>
                <select name="viabilisee" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Tous</option>
                    <option value="1" {{ old('viabilisee', $filters['viabilisee'] ?? '') === '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('viabilisee', $filters['viabilisee'] ?? '') === '0' ? 'selected' : '' }}>Non</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Prix min</label>
                <input type="number" name="prix_min" value="{{ old('prix_min', $filters['prix_min'] ?? '') }}" placeholder="0"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Prix max</label>
                <input type="number" name="prix_max" value="{{ old('prix_max', $filters['prix_max'] ?? '') }}" placeholder="100000000"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Superficie min</label>
                <input type="number" name="superficie_min" value="{{ old('superficie_min', $filters['superficie_min'] ?? '') }}" placeholder="0"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="w-full bg-emerald-600 text-white rounded-xl px-4 py-2 text-sm font-semibold hover:bg-emerald-700 transition-colors duration-200">Filtrer</button>
                <a href="{{ route('parcelles.index') }}"
                    class="w-full bg-gray-100 text-gray-700 rounded-xl px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition-colors duration-200 text-center">Réinitialiser</a>
            </div>
        </div>
    </form>

    @if ($parcelles->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.553-1.949l5-1.25A2 2 0 0110 2.5h4a2 2 0 01.447.919l5 1.25A2 2 0 0121 5.618v9.764a2 2 0 01-1.553 1.894L14 20m-5 0v-7m5 7v-7" />
            </svg>
            <p class="text-lg font-semibold">Aucune parcelle trouvée</p>
            <p class="text-sm mt-1">Essayez de modifier vos filtres</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($parcelles as $parcelle)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col">
                    <div class="relative h-48 bg-gray-100">
                        @if ($parcelle->imagePrincipale)
                            <img src="{{ $parcelle->imagePrincipale->url }}" alt="{{ $parcelle->titre }}"
                                class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75L12 3l9 6.75V21H3V9.75z" />
                                </svg>
                                <span class="text-sm mt-2">Pas d'image</span>
                            </div>
                        @endif

                        <span class="absolute top-3 left-3 text-xs font-semibold px-2 py-1 rounded-full {{ $parcelle->statut === 'vendu' ? 'bg-red-100 text-red-700' : ($parcelle->statut === 'réservé' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($parcelle->statut) }}
                        </span>
                        @if ($parcelle->viabilisee)
                            <span class="absolute top-3 right-3 text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                Viabilisée
                            </span>
                        @endif
                    </div>

                    <div class="p-4 flex flex-col gap-2 flex-1">
                        <h3 class="text-base font-bold text-gray-800 line-clamp-1">{{ $parcelle->titre }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-1">{{ $parcelle->quartier }}, {{ $parcelle->ville }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm text-gray-600">{{ number_format($parcelle->superficie, 0, ',', ' ') }} m²</span>
                            <span class="text-xs text-gray-400">{{ $parcelle->reference }}</span>
                        </div>
                        <p class="mt-auto text-lg font-bold text-emerald-600">{{ number_format($parcelle->prix, 0, ',', ' ') }} FCFA</p>
                        <a href="{{ route('parcelles.show', $parcelle) }}"
                            class="mt-3 w-full bg-primary text-white text-sm font-semibold py-2 rounded-xl text-center hover:bg-primary/90 transition-colors duration-200">
                            Voir les détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $parcelles->withQueryString()->links() }}
        </div>
    @endif
</div>
