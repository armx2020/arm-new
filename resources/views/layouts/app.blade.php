<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')

    @yield('title')

    <link type="image/png" sizes="16x16" rel="icon" href="{{ url('image/favicon-16x16.png') }}">
    <link type="image/png" sizes="32x32" rel="icon" href="{{ url('image/favicon-32x32.png') }}">
    <link type="image/png" sizes="96x96" rel="icon" href="{{ url('image/favicon-96x96.png') }}">
    <link type="image/png" sizes="120x120" rel="icon" href="{{ url('image/favicon-120x120.png') }}">

    <!-- Fonts with optimized loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;800&display=swap" rel="stylesheet">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ url('/jquery-3.7.0.min.js') }}" as="script">
    
    <!-- Scripts with defer for non-blocking load -->
    <script src="{{ url('/jquery-3.7.0.min.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('scripts')

    @include('layouts.yandex')
</head>

<body class="antialiased w-full">
    <div class="bg-[#F5F7FA]">
        @include('layouts.nav')
        <div class="w-11/12 lg:w-10/12 max-w-7xl mx-auto min-h-[40rem]">
            @yield('content')
        </div>
        @include('layouts.footer')
    </div>
    @vite(['resources/js/scripts.js'])

    @yield('body')

</body>
</html>
