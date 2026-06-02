<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @fonts
    @ddfsnStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav x-data="{ isOpen: false }" class="relative py-5 border-b border-white position-sticky top-0 z-100 backdrop-blur-sm">
        <div class="hidden md:flex justify-between items-center max-w-7xl mx-auto px-6">
            <a href="{{ route('index') }}" class="text-2xl font-bold text-primary">Samaritain</a>
            <div class="flex items-center gap-6">
                <a href="{{ route('index') }}" class="hover:text-primary">Accueil</a>
                <a href="#" class="hover:text-primary">Parcelles à vendre</a>
                <a href="#" class="hover:text-primary">Services</a>
                <a href="tel:+242068007138"
                    class="bg-primary text-white py-2.5 px-5 font-semibold rounded-4xl hover:bg-secondary">
                    <i class="fas fa-phone-alt"></i> +242 06 800 71 38
                </a>
                @if (auth()->user())
                    <form action="{{ route('logout') }}" method="POST">
                        <x-btn type="submit" style="outline">Se déconnecter</x-btn>
                    </form>
                @else
                    <div>
                        <x-btn href="{{ route('login') }}" style='outline'>Se connecter</x-btn>
                        <x-btn href="{{ route('register') }}">S'inscrire</x-btn>
                    </div>
                @endif
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div class="flex md:hidden justify-between items-center max-w-7xl mx-auto px-6">
            <a href="{{ route('index') }}" class="text-2xl font-bold">Samaritain</a>

            <div class="flex items-center gap-3">
                @if (auth()->user() && auth()->user()->profile_image)
                    <img src="{{ auth()->user()->profile_image }}" alt="{{ auth()->user()->name }}" class="w-7 h-7 rounded-full shrink-0 object-cover border border-zinc-700 shadow-sm">
                @elseif (auth()->user())
                    <p class="w-7 h-7 rounded-full shrink-0 object-cover border border-zinc-700 shadow-sm">{{ auth()->user()->name[0] }}</p>
                @endif
                @unless (auth()->user())
                    <x-btn x-show="!isOpen" style="outline" href="{{ route('login') }}">Se connecter</x-btn>
                @endunless
                <button @click="isOpen = !isOpen" :aria-expanded="isOpen" class="focus:outline-none">
                    <i x-show="!isOpen" data-lucide="menu"></i>
                    <i x-show="isOpen" data-lucide="x"></i>
                </button>
            </div>
        </div>

        <div x-show="isOpen" x-cloak @click.outside="isOpen = false" class="md:hidden absolute inset-x-0 top-full z-50 border-b rounded-b-xl border-white bg-background shadow-xl">
            <div class="flex flex-col gap-4 max-w-7xl mx-auto px-6 pb-6 pt-4">
                <a href="{{ route('index') }}" class="block text-base font-medium hover:text-primary">Accueil</a>
                <a href="#" class="block text-base font-medium hover:text-primary">Parcelles à vendre</a>
                <a href="#" class="block text-base font-medium hover:text-primary">Services</a>
                <a href="tel:+242068007138" class="block bg-primary text-white py-3 px-4 font-semibold rounded-4xl text-center hover:bg-secondary">
                    <i class="fas fa-phone-alt"></i> +242 06 800 71 38
                </a>
                @if (auth()->user())
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <x-btn type="submit" style="outline" class="w-full">Se déconnecter</x-btn>
                    </form>
                @else
                    <div class="flex flex-col gap-3">
                        <x-btn href="{{ route('login') }}" style="outline" class="w-full">Se connecter</x-btn>
                        <x-btn href="{{ route('register') }}" class="w-full">S'inscrire</x-btn>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    {{-- Mobile Bottom Navigation --}}
    <div class="fixed bottom-0 inset-x-0 md:hidden flex justify-around items-center bg-background border-t border-input-border py-3 z-40">
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary transition">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span class="text-xs">Accueil</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary transition">
            <i data-lucide="search" class="w-5 h-5"></i>
            <span class="text-xs">Recherche</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary transition">
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span class="text-xs">Mises à jour</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary transition">
            <i data-lucide="heart" class="w-5 h-5"></i>
            <span class="text-xs">Favoris</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary transition">
            <i data-lucide="inbox" class="w-5 h-5"></i>
            <span class="text-xs">Messages</span>
        </a>
    </div>

    {{-- Padding for bottom navigation --}}
    <div class="md:hidden h-24"></div>
</body>

</html>
