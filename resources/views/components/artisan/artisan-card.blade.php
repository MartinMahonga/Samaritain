@props(['artisan'])

<div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300">
    <!-- Cover -->
    <div class="relative h-32 bg-gradient-to-br from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900">
        @if($artisan->cover)
            <img src="{{ Storage::url($artisan->cover) }}" alt="{{ $artisan->business_name }}" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        
        @if($artisan->verified)
            <div class="absolute top-3 right-3 bg-green-400 dark:bg-green-500 text-green-600 dark:text-green-700 p-1.5 rounded-full shadow-lg" title="Artisan vérifié">
                <i data-lucide="badge-check" class="h-4 w-4"></i>
            </div>
        @endif
    </div>

    <!-- Avatar -->
    <div class="px-4 -mt-8 relative z-10">
        <div class="w-16 h-16 rounded-full border-4 border-white dark:border-gray-800 shadow-md overflow-hidden bg-gray-100 dark:bg-gray-700">
            @if($artisan->avatar)
                <img src="{{ Storage::url($artisan->avatar) }}" alt="{{ $artisan->business_name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-300 text-xl font-bold">
                    {{ substr($artisan->business_name, 0, 1) }}
                </div>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 pb-4 pt-2">
        <h3 class="font-semibold text-gray-900 dark:text-white text-lg truncate">{{ $artisan->business_name }}</h3>
        <p class="text-primary dark:text-primary-400 text-sm font-medium">{{ $artisan->profession }}</p>
        <p class="text-gray-500 dark:text-gray-400 text-sm flex items-center gap-1 mt-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ $artisan->city }}
        </p>

        <!-- Rating -->
        <div class="flex items-center gap-2 mt-2">
            <div class="flex items-center gap-1">
                <div class="flex text-amber-400 dark:text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $artisan->average_rating)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @elseif($i - 0.5 <= $artisan->average_rating)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0v15z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($artisan->average_rating, 1) }}</span>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500">({{ $artisan->reviews_count }} avis)</span>
        </div>

        <a href="{{ route('artisans.show', $artisan) }}" class="mt-3 block w-full text-center bg-primary dark:bg-primary-600 text-white py-2 rounded-xl hover:bg-primary/95 dark:hover:bg-primary-700 transition-colors font-medium text-sm">
            Voir le profil
        </a>
    </div>
</div>