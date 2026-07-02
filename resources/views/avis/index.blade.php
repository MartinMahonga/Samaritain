@extends('layouts.base')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">

        {{-- En-tête --}}
        <div class="flex items-center justify-center gap-2 mb-8">
            <i data-lucide="message-square-heart" class="w-6 h-6 text-primary"></i>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Suggestions & Avis</h1>
        </div>

        {{-- Message succès --}}
        @if (session('success'))
            <div
                class="bg-primary/10 border border-primary/30 text-primary dark:text-orange-300 px-4 py-3 rounded-lg mb-6 flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Formulaire --}}
        @auth
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8"
                x-data="{ note: {{ old('note', 5) }}, hover: 0 }">

                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-1">
                    Votre satisfaction est notre priorité
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                    Partagez votre expérience et aidez-nous à améliorer nos services
                </p>

                <form action="{{ route('avis.store') }}" method="POST">
                    @csrf

                    {{-- Note (étoiles interactives en SVG) --}}
                    <div class="flex flex-col gap-2 mb-5">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Note</label>

                        <input type="hidden" name="note" x-model="note">

                        <div class="flex items-center gap-1">
                            <template x-for="i in 5" :key="i">
                                <button type="button" @click="note = i" @mouseenter="hover = i"
                                    @mouseleave="hover = 0" class="p-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        class="w-7 h-7 transition-colors"
                                        :class="(hover || note) >= i ? 'text-yellow-400 fill-yellow-400' :
                                            'text-gray-300 dark:text-gray-600 fill-transparent'"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z" />
                                    </svg>
                                </button>
                            </template>

                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400"
                                x-text="[
                                '', 'Mauvais', 'Passable', 'Bien', 'Très bien', 'Excellent'
                            ][note]"></span>
                        </div>

                        @error('note')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Commentaire --}}
                    <div class="flex flex-col gap-1 mb-5">
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Commentaire</label>
                        <textarea name="commentaire" rows="4" placeholder="Écrivez votre avis ici..."
                            class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary resize-none">{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-orange-500 transition">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Envoyer
                    </button>
                </form>
            </div>
        @endauth

        {{-- Liste des avis (visible uniquement par l'admin) --}}
        @if (auth()->user()->is_staff)
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i data-lucide="list" class="w-5 h-5"></i>
                    Tous les avis
                    <span class="text-sm font-normal text-gray-400">({{ $avis->count() }})</span>
                </h2>

                @forelse($avis as $a)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">

                        {{-- En-tête : avatar + nom + date --}}
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold text-sm shrink-0">
                                    {{ strtoupper(substr($a->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        {{ $a->user->name ?? 'Anonyme' }}
                                    </p>
                                    <p class="text-xs text-gray-400 flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        {{ $a->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Note (étoiles en SVG) --}}
                        <div class="flex items-center gap-0.5 mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 {{ $i <= $a->note ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 dark:text-gray-600 fill-transparent' }}"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z" />
                                </svg>
                            @endfor
                        </div>

                        {{-- Commentaire --}}
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">{{ $a->commentaire }}</p>

                        {{-- Bouton supprimer --}}
                        <form action="{{ route('avis.destroy', $a) }}" method="POST"
                            onsubmit="return confirm('Supprimer cet avis ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 text-xs text-red-600 border border-red-200 dark:border-red-900 px-3 py-1.5 rounded-lg hover:bg-red-600 hover:text-white transition">
                                <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                Supprimer
                            </button>
                        </form>

                    </div>
                @empty
                    <div class="text-center text-gray-400 py-12 flex flex-col items-center gap-2">
                        <i data-lucide="inbox" class="w-8 h-8"></i>
                        <p>Aucun avis pour le moment.</p>
                    </div>
                @endforelse
            </div>
        @endif

    </div>
@endsection