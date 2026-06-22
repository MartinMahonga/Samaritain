<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-primary dark:bg-primary-700 text-white overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i data-lucide="images" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Mes réalisations</h1>
                        <p class="text-white/90 dark:text-white/90 mt-1">Gérez vos photos de projets et chantiers</p>
                    </div>
                </div>
                <x-btn href="{{ route('artisan.projects.create', $artisan) }}" style="secondary">
                    <x-slot:prefix>
                        <i data-lucide="plus"></i>
                    </x-slot:prefix>
                    Ajouter une réalisation
                </x-btn>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($projects->isNotEmpty())
            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950/40 dark:to-blue-900/30 rounded-xl p-4 text-center">
                    <i data-lucide="folder" class="w-8 h-8 text-primary dark:text-primary-400 mx-auto mb-2"></i>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $projects->total() }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Projet(s) réalisé(s)</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950/40 dark:to-green-900/30 rounded-xl p-4 text-center">
                    <i data-lucide="image" class="w-8 h-8 text-green-600 dark:text-green-400 mx-auto mb-2"></i>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $projects->where('image', '!=', null)->count() }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Photo(s) publiée(s)</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950/40 dark:to-purple-900/30 rounded-xl p-4 text-center">
                    <i data-lucide="calendar" class="w-8 h-8 text-purple-600 dark:text-purple-400 mx-auto mb-2"></i>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $projects->where('created_at', '>=', now()->subMonth())->count() }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Ajout(s) ce mois</p>
                </div>
            </div>

            <!-- Grille des réalisations -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 dark:hover:shadow-gray-900/50">
                        <div class="relative h-52 overflow-hidden">
                            @if ($project->image)
                                <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                    <i data-lucide="image" class="w-16 h-16"></i>
                                </div>
                            @endif
                            
                            <!-- Badge date -->
                            <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-lg">
                                {{ $project->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-2 line-clamp-1">{{ $project->title }}</h3>
                            @if ($project->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ Str::limit($project->description, 80) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between gap-3 mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('artisan.projects.edit', [$artisan, $project]) }}"
                                    class="inline-flex items-center gap-1 text-primary dark:text-primary-400 hover:text-primary/80 dark:hover:text-primary-300 text-sm font-medium transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                    Modifier
                                </a>
                                <form action="{{ route('artisan.projects.destroy', [$artisan, $project]) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Supprimer cette réalisation ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="mt-8 text-gray-600 dark:text-gray-400">
                    {{ $projects->links() }}
                </div>
            @endif
        @else
            <!-- État vide amélioré -->
            <div class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="images" class="w-12 h-12 text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Aucune réalisation</h3>
                <p class="text-gray-400 dark:text-gray-500 mb-6 max-w-md mx-auto">
                    Partagez vos travaux et projets avec la communauté. 
                    Les photos de vos réalisations valorisent votre savoir-faire.
                </p>
                <x-btn href="{{ route('artisan.projects.create', $artisan) }}" class="dark:bg-primary-600 dark:text-white dark:hover:bg-primary-700">
                    <x-slot:prefix>
                        <i data-lucide="plus"></i>
                    </x-slot:prefix>
                    Ajouter ma première réalisation
                </x-btn>
            </div>
        @endif

        <!-- Conseils -->
        @if($projects->isNotEmpty())
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 rounded-xl p-5 border border-blue-100 dark:border-blue-900/30">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Conseils pour valoriser vos réalisations</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Des photos de qualité augmentent votre crédibilité. Ajoutez des descriptions détaillées 
                            et mettez à jour régulièrement votre portfolio pour attirer plus de clients.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>