@extends('layouts.base')

@section('title', 'Accueil')

@section('content')
    <x-blade-components::layout.container>
        <section>
            <nav class="flex items-center gap-2 py-4 md:py-6">
                <a href="{{ route('property.dashboard') }}" @class([
                    'hover:border-b-4 hover:text-primary hover:border-primary hover:bg-primary/7 rounded-xs px-2 py-1 text-sm flex items-center gap-1',
                    'border-b-4 text-primary border-primary bg-primary/7' =>
                        request()->route()->getName() === 'property.dashboard',
                ])>
                    <i data-lucide="warehouse" class="w-4 h-4"></i>
                    Mes biens
                </a>
                @if (auth()->user()?->artisan)
                    <a href="{{ route('artisan.dashboard') }}" @class([
                        'hover:border-b-4 hover:text-primary hover:border-primary hover:bg-primary/7 rounded-xs px-2 text-sm py-1 text-sm flex items-center gap-1',
                        'border-b-4 text-primary border-primary bg-primary/7' =>
                            request()->route()->getName() === 'artisan.dashboard',
                    ])>
                        <i data-lucide="drill" class="w-4 h-4"></i>
                        Mon profil artisan
                    </a>
                @endif
                <a href="#" @class([
                    'hover:border-b-4 hover:text-primary hover:border-primary hover:bg-primary/7 rounded-xs px-2 text-sm py-1 text-sm flex items-center gap-1',
                    'border-b-4 text-primary border-primary bg-primary/7' =>
                        request()->route()->getName() === 'property.dashboard',
                ])>
                    <i data-lucide="activity" class="w-4 h-4"></i>
                    Mes activités
                </a>
            </nav>
        </section>
        <section>
            @yield('content')
        </section>
    </x-blade-components::layout.container>
@endsection
