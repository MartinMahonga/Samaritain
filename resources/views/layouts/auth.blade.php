<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | Samaritain</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav>
        <a href="{{ route('index') }}">
            <img src="{{ asset('light_logo.svg') }}" alt="light logo"
                class="w-18 px-3 py-3 block dark:hidden">
            <img src="{{ asset('dark_logo.svg') }}" alt="dark logo"
                class="w-18 px-3 py-3 dark:block hidden">
        </a>
    </nav>
    @yield('content')
</body>

</html>
