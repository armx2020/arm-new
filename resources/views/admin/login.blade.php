<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('VSE ARMYANE', 'sign in') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen bg-gray-100">

    <div class="min-h-screen flex flex-col sm:justify-center items-center">

        <form method="POST" action="{{ route('admin.login') }}" class="w-full max-w-sm m-auto">
            @csrf


            @if (session('status'))
            <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-500">
                {{ session('status') }}
            </div>
            @endif

            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Login
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-cyan-500" id="inline-full-name" type="text" name="login">
                </div>
            </div>

            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3">
                    <label class="block font-bold md:text-right mb-1 md:mb-0 pr-4">
                        Password
                    </label>
                </div>
                <div class="md:w-2/3">
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-cyan-500" id="inline-password" type="password" name="password">
                </div>
            </div>
            <div class="md:flex md:items-center mb-6">
                <div class="md:w-1/3"></div>
                <label class="md:w-2/3 block font-bold">
                    <input class="mr-2 leading-tight" type="checkbox" name="remember">
                    <span class="text-sm">
                        remember me
                    </span>
                </label>
            </div>
            <div class="md:flex md:items-center">
                <div class="md:w-1/3"></div>
                <div class="md:w-2/3">
                    <button class="shadow bg-cyan-600 hover:bg-cyan-700 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                        Sign in
                    </button>
                </div>
            </div>
        </form>

    </div>

</body>

</html>