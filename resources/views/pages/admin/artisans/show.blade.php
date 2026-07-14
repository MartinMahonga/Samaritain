@extends('layouts.dashboard')

@section('title', $artisan->business_name)

@section('content')
    <div>
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.artisans.index') }}"
                class="text-primary dark:text-primary-400 text-xs font-medium mb-2 inline-block hover:text-primary-700 dark:hover:text-primary-300">
                &larr; Retour à la liste
            </a>
            <div class="flex items-center justify-between">
                <h1 class="md:text-2xl text-lg font-bold text-gray-700 dark:text-gray-200">{{ $artisan->business_name }}</h1>
                <div class="flex gap-2">
                    @if (!$artisan->verified)
                        <form action="{{ route('admin.artisans.verify', $artisan) }}" method="POST">
                            @csrf
                            <x-btn type="submit" size="sm" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                                <x-slot:prefix>
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                </x-slot:prefix>
                                Valider l'artisan
                            </x-btn>
                        </form>
                    @endif
                    <form action="{{ route('admin.artisans.suspend', $artisan) }}" method="POST">
                        @csrf
                        <x-btn type="submit" style="warning" size="sm" class="dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700">
                            <x-slot:prefix>
                                <i data-lucide="{{ $artisan->is_active ? 'pause-circle' : 'play-circle' }}" class="w-4 h-4"></i>
                            </x-slot:prefix>
                            {{ $artisan->is_active ? 'Suspendre' : 'Activer' }}
                        </x-btn>
                    </form>
                    <a href="{{ route('admin.artisans.edit', $artisan) }}"
                        class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                        <x-btn type="button" style="primary" size="sm" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                            <x-slot:prefix>
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </x-slot:prefix>
                            Modifier
                        </x-btn>
                    </a>
                    <form action="{{ route('admin.artisans.destroy', $artisan) }}" method="POST"
                        onsubmit="return confirm('Supprimer définitivement cet artisan ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <x-btn type="submit" style="destructive" size="sm" class="dark:bg-red-600 dark:text-white dark:hover:bg-red-700">
                            <x-slot:prefix>
                                <i data-lucide="trash" class="w-4 h-4"></i>
                            </x-slot:prefix>
                            Supprimer
                        </x-btn>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Infos -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Informations</h2>
                    <dl class="grid grid-cols-2 gap-4">
                        <div class="text-sm">
                            <dt class="text-gray-500 dark:text-gray-400">Profession</dt>
                            <dd class="font-medium text-gray-700 dark:text-gray-300">{{ $artisan->profession }}</dd>
                        </div>
                        <div class="text-sm">
                            <dt class="text-gray-500 dark:text-gray-400">Ville</dt>
                            <dd class="font-medium text-gray-700 dark:text-gray-300">{{ $artisan->city }}</dd>
                        </div>
                        <div class="text-sm">
                            <dt class="text-gray-500 dark:text-gray-400">Téléphone</dt>
                            <dd class="font-medium text-gray-700 dark:text-gray-300">{{ $artisan->phone }}</dd>
                        </div>
                        <div class="text-sm">
                            <dt class="text-gray-500 dark:text-gray-400">WhatsApp</dt>
                            <dd class="font-medium text-gray-700 dark:text-gray-300">{{ $artisan->whatsapp ?? '-' }}</dd>
                        </div>
                        <div class="text-sm">
                            <dt class="text-gray-500 dark:text-gray-400">Expérience</dt>
                            <dd class="font-medium text-gray-700 dark:text-gray-300">
                                {{ $artisan->experience ? $artisan->experience . ' an(s)' : '-' }}
                            </dd>
                        </div>
                        <div class="text-sm">
                            <dt class="text-gray-500 dark:text-gray-400">Statut</dt>
                            <dd>
                                @if ($artisan->verified)
                                    <span class="px-2 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">Vérifié</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-600 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">En attente</span>
                                @endif
                                @if (!$artisan->is_active)
                                    <span class="px-2 py-1 text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 rounded-full ml-1">Suspendu</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Catégories -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Spécialités</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($artisan->categories as $category)
                            <span class="px-2 py-1 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                {{ $category->name }}
                            </span>
                        @endforeach
                        @if($artisan->categories->isEmpty())
                            <p class="text-sm text-gray-400 dark:text-gray-500">Aucune spécialité renseignée</p>
                        @endif
                    </div>
                </div>

                <!-- Bio -->
                @if ($artisan->bio)
                    <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Description</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $artisan->bio }}</p>
                    </div>
                @endif

                <!-- Réalisations -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            Réalisations <span class="text-gray-400 dark:text-gray-500 text-sm">({{ $artisan->projects->count() }})</span>
                        </h2>
                        <a href="{{ route('admin.artisans.projects.create', $artisan) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium flex items-center gap-1">
                            <i data-lucide="plus" class="w-3 h-3"></i>
                            Ajouter
                        </a>
                    </div>
                    @if ($artisan->projects->isNotEmpty())
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($artisan->projects as $project)
                                <div class="relative group aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @php
                                        $projectImages = $project->images->pluck('image_path');
                                        $firstImage = $projectImages->first() ?? $project->image;
                                    @endphp
                                    @if ($firstImage)
                                        <img src="{{ Storage::url($firstImage) }}" alt="{{ $project->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <div class="text-center">
                                                <span class="text-white text-xs font-medium px-2 py-1 bg-black/70 rounded">{{ $project->title }}</span>
                                                @if ($projectImages->count() > 1)
                                                    <span class="text-white text-xs block mt-1">+{{ $projectImages->count() - 1 }} photos</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">
                                            <i data-lucide="image" class="w-8 h-8"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="camera" class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Aucune réalisation</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Propriétaire / Créateur -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Propriétaire</h2>
                    @if ($artisan->user)
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($artisan->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $artisan->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $artisan->user->email }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            Membre depuis {{ $artisan->user->created_at->format('d/m/Y') }}
                        </p>
                    @else
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 dark:from-gray-500 dark:to-gray-600 flex items-center justify-center text-white font-bold text-lg">
                                <i data-lucide="building" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Profil administré</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Créé par l'administration</p>
                            </div>
                        </div>
                        @if ($artisan->createdBy)
                            <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                <i data-lucide="user" class="w-3 h-3"></i>
                                Par {{ $artisan->createdBy->name }}
                            </p>
                        @endif
                    @endif
                </div>

                <!-- Stats rapides -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Statistiques</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $artisan->reviews_count ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Avis</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-amber-500 dark:text-amber-400">{{ number_format($artisan->average_rating ?? 0, 1) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Note moyenne</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $artisan->contacts_count ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Contacts</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $artisan->projects->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Réalisations</div>
                        </div>
                    </div>
                </div>

                <!-- Avis récents -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Derniers avis</h2>
                    @if ($artisan->reviews->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($artisan->reviews->take(5) as $review)
                                <div class="pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $review->user->name }}</span>
                                        <div class="flex text-amber-400 dark:text-amber-400 gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                                @else
                                                    <i data-lucide="star" class="w-3 h-3 text-gray-300 dark:text-gray-600"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ Str::limit($review->comment, 80) }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                        @if($artisan->reviews->count() > 5)
                            <div class="mt-3 text-center">
                                <a href="#" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Voir tous les avis</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <i data-lucide="message-circle" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Aucun avis</p>
                        </div>
                    @endif
                </div>

                <!-- Demandes de contact -->
                <div class="bg-sidebar dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Demandes de contact</h2>
                    @if ($artisan->contacts->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($artisan->contacts->take(5) as $contact)
                                <div class="pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $contact->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $contact->phone }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $contact->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                        @if($artisan->contacts->count() > 5)
                            <div class="mt-3 text-center">
                                <a href="#" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Voir toutes les demandes</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <i data-lucide="phone" class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Aucune demande</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection