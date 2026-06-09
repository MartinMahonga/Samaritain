<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Samaritain</title>
    @fonts
    @ddfsnStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col">
    <x-ui.navbar/>

    <main class="flex-1">
        @yield('content')
    </main>
    
    <x-ui.footer/>
    <x-ui.mobile-nav/>
</body>

</html>
