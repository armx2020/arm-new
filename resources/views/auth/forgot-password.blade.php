@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Восстановление пароля</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Восстановление пароля">
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
                Восстановление пароля
            </p>
        </div>    

        <hr class="my-4">

        @if (session('error'))
            <x-input-error :messages="session('error')" class="mt-2 mb-3" />
        @endif

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('forgot-password') }}">
            @csrf

            <div class="mb-4 text-sm text-gray-600">
                {{ __('Забыли свой пароль? Без проблем. Просто подтвердите свой номер телефона.') }}
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <x-input-label for="phone" :value="__('Телефон')" />
                <x-text-input id="phone" class="block my-2 w-full mask-phone" type="tel" name="phone"
                    :value="old('phone')" required autofocus autocomplete="phone" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Подтвердить') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
@section('body')
    @vite(['resources/js/mask_phone.js'])
@endsection
