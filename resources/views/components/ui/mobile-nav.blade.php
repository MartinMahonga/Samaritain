<div class="fixed bottom-0 inset-x-0 md:hidden z-40 bg-white/95 backdrop-blur-md border-t border-gray-100 safe-area-pb">
    <div class="flex justify-around items-center py-2 px-2">

        <a href="{{ route('index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-primary">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Accueil</span>
        </a>

        <a href="#"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-gray-400 hover:text-primary transition">
            <i data-lucide="search" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Recherche</span>
        </a>

        {{-- Bouton central proéminent --}}
        <a href="tel:+242068007138" class="flex flex-col items-center gap-0.5 -mt-5">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center shadow-lg shadow-primary/30">
                <i data-lucide="phone" class="w-5 h-5 text-white"></i>
            </div>
            <span class="text-[10px] font-medium text-primary mt-0.5">Appeler</span>
        </a>

        <a href="{{ route('favorite') }}"
            class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-gray-400 hover:text-primary transition">
            <i data-lucide="heart" class="w-5 h-5"></i>
            <span class="text-[10px] font-medium">Favoris</span>
        </a>

        @if (auth()->user())
            <a href="#"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-gray-400 hover:text-primary transition">
                @if (auth()->user()->profile_image)
                    <img src="{{ auth()->user()->profile_image }}" alt="{{ auth()->user()->name }}"
                        class="w-5 h-5 rounded-full object-cover">
                @else
                    <div
                        class="w-5 h-5 rounded-full bg-primary/10 text-primary font-bold text-[10px] flex items-center justify-center">
                        {{ strtoupper(auth()->user()->name[0]) }}
                    </div>
                @endif
                <span class="text-[10px] font-medium">Profil</span>
            </a>
        @else
            <a href="{{ route('login') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-gray-400 hover:text-primary transition">
                <i data-lucide="user" class="w-5 h-5"></i>
                <span class="text-[10px] font-medium">Connexion</span>
            </a>
        @endif
    </div>
</div>

{{-- Espace pour bottom nav mobile --}}
<div class="md:hidden h-20"></div>
