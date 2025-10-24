<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Все армяне - АДМИН-ПАНЕЛЬ</title>

    <link type="image/png" sizes="16x16" rel="icon" href="{{ url('image/favicon-16x16.png') }}">
    <link type="image/png" sizes="32x32" rel="icon" href="{{ url('image/favicon-32x32.png') }}">
    <link type="image/png" sizes="96x96" rel="icon" href="{{ url('image/favicon-96x96.png') }}">
    <link type="image/png" sizes="120x120" rel="icon" href="{{ url('image/favicon-120x120.png') }}">

    <!-- Fonts with optimized loading -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Preload critical scripts -->
    <link rel="preload" href="{{ url('/jquery-3.7.0.min.js') }}" as="script">
    <link rel="preload" href="{{ url('/select2.min.js') }}" as="script">
    
    <script src="{{ url('/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('/select2.min.js') }}"></script>
    <script src="{{ url('/jquery.maskedinput.min.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/select.css'])
    @livewireStyles

    @if (request()->routeIs('admin.entity.create') || request()->routeIs('admin.entity.edit'))
         <script src="https://api-maps.yandex.ru/2.1/?apikey={{ config('services.yandex.geocoder_key') }}&lang=ru_RU"
            type="text/javascript"></script>
    @endif
</head>

<body class="font-sans antialiased">
    @include('admin.layouts.navigation')

    <!-- Page Content -->
    <main class="bg-gray-100 w-full min-h-screen">
        @yield('content')
    </main>

    @livewireScripts
</body>

</html>
