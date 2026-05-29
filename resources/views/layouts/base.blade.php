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
    <nav class="py-5 border-b border-white position-sticky top-0 z-100 backdrop-blur-sm">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-6">
            <a href="#" class="text-2xl font-bold text-primary">Samaritain</a>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-primary">Accueil</a>
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
    </nav>

    <main>
        @yield('content')
    </main>
</body>

</html>
