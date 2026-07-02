@extends('layouts.base')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex justify-center">Suggestions & Avis</h1>

        {{-- Message succès --}}
        @if(session('success'))
            <div class="bg-primary text-white px-4 py-3  mb-6 flex justify-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Formulaire --}}
        @auth
            <div class="bg-white dark:bg-gray-800  shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Votre satisfaction est notre priorité —
                    partagez votre expérience et aidez-nous à améliorer nos services</h2>

                <form action="{{ route('avis.store') }}" method="POST">
                    @csrf

                    {{-- Note --}}
                    <div class="flex flex-col gap-1 mb-4">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Note</label>
                        <select name="note"
                            class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                            <option value="4">⭐⭐⭐⭐ Très bien</option>
                            <option value="3">⭐⭐⭐ Bien</option>
                            <option value="2">⭐⭐ Passable</option>
                            <option value="1">⭐ Mauvais</option>
                        </select>
                        @error('note')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Commentaire --}}
                    <div class="flex flex-col gap-1 mb-4">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Commentaire</label>
                        <textarea name="commentaire" rows="4" placeholder="Écrivez votre avis ici..."
                            class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-orange-500 transition">
                        Envoyer
                    </button>
                </form>
            </div>
        @endauth

        {{-- Liste des avis (visible uniquement par l'admin) --}}
        @if(auth()->user()->is_staff)
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Tous les avis ({{ $avis->count() }})</h2>

                @forelse($avis as $a)
                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 p-5">

                        {{-- En-tête : nom + date --}}
                        <div class="flex items-center justify-between mb-2">
                            ...
                        </div>

                        {{-- Note --}}
                        <div class="text-yellow-400 text-sm mb-2">
                            {{ str_repeat('⭐', $a->note) }}
                        </div>

                        {{-- Commentaire --}}
                        <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $a->commentaire }}</p>

                        {{-- Bouton supprimer (ajoute ICI) --}}
                        <form action="{{ route('avis.destroy', $a) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-xs text-primary border border-red-200 px-3 py-1 rounded-xs hover:bg-red-700 transition">
                                Supprimer
                            </button>
                        </form>

                    </div>
                @empty
                    <p class="text-center text-gray-400 py-8">Aucun avis pour le moment.</p>
                @endforelse
            </div>
        @endif

    </div>
@endsection