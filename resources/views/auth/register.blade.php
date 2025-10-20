@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Мои общины</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Мои общины">
@endsection

@section('scripts')
    <script src="{{ url('/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ url('/jquery.maskedinput.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endsection

@section('content')
    <div class="w-full sm:max-w-lg p-4 bg-white overflow-hidden sm:rounded-lg z-50 mx-auto my-10 md:my-20">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="flex items-center justify-between mb-2">
                <p class="text-lg font-bold text-gray-900">
                    РЕГИСТРАЦИЯ
                </p>
            </div>

            <hr class="my-4">

            @if (session('error'))
                <x-input-error :messages="session('error')" class="mt-2 mb-3" />
            @endif

            <!-- First Name -->
            <div>
                <x-input-label for="firstname" :value="__('Имя')" />
                <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')"
                    required autofocus autocomplete="firstname" />
                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <x-input-label for="phone" :value="__('Телефон')" />
                <x-text-input id="phone" class="block my-2 w-full mask-phone" type="tel" name="phone"
                    :value="old('phone')" required autofocus autocomplete="phone" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Пароль')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Подтвердите пароль')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}">
                    {{ __('Уже зарегистрированы?') }}
                </a>

                <x-primary-button class="ml-4" id="primary-button">
                    {{ __('Регистрация') }}
                </x-primary-button>
            </div>
            <hr class="my-2">

            <div class="flex mt-2">
                <label for="confirm_policy" class="inline-flex items-center">
                    <span class="text-center text-xs text-gray-600">Создавая учетную запись, я соглашаюсь с <a
                            href="{{ route('privacy-policy') }}" target="_blank" class="text-indigo-400 underline">Политикой
                            конфиденциальности</a> и <a href="{{ route('condition-of-use') }}" target="_blank"
                            class="text-indigo-400 underline">Условием иcпользования</a>.</span>
                </label>
            </div>
        </form>
    </div>
@endsection

@section('body')
@vite(['resources/js/mask_phone.js'])
@endsection
