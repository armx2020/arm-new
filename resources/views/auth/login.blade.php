@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - ВХОД</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - ВХОД">
@endsection

@section('scripts')
    <script src="{{ url('/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('/jquery.maskedinput.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endsection

@section('content')
    <div class="w-full sm:max-w-lg p-4 bg-white overflow-hidden sm:rounded-lg z-50 mx-auto my-10 md:my-20">

        <div class="flex items-center justify-between mb-2">
            <p class="text-lg font-bold text-gray-900">
                ВХОД
            </p>
        </div>   

        <hr class="my-4">
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if (session('error'))
                <x-input-error :messages="session('error')" class="mt-2 mb-3" />
            @endif

            <!-- Phone -->
            <div>
                <x-input-label for="phone" :value="__('Телефон')" />
                <x-text-input id="Phone" class="block mt-1 w-full mask-phone" type="tel" name="phone"
                    :value="old('phone')" placeholder="Введите номер телефона" required autofocus autocomplete="phone" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Пароль')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Пароль"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <!-- Remember Me -->
                <div class="flex">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Запомнить меня') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end">
                    @if (Route::has('forgot-password'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('forgot-password') }}">
                            {{ __('Забыли пароль?') }}
                        </a>
                    @endif

                    <x-primary-button class="ml-3">
                        {{ __('Далее') }}
                    </x-primary-button>
                </div>

            </div>
        </form>
    </div>
@endsection

@section('body')
    @vite(['resources/js/mask_phone.js'])
@endsection
