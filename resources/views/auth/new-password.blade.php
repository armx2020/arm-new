@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - НОВЫЙ ПАРОЛЬ</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Новый пароль">
@endsection

@section('scripts')
@endsection

@section('content')
    <div class="w-full sm:max-w-lg p-4 bg-white overflow-hidden sm:rounded-lg z-50 mx-auto my-10 md:my-20">

        <div class="flex items-center justify-between mb-2">
            <p class="text-lg font-bold text-gray-900">
                Новый пароль
            </p>
        </div>

        <hr class="my-4">

        <form method="POST" action="{{ route('new-password.store') }}">
            @csrf
            
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Введите новый пароль') }}
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

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Подтвердить') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
@section('body')
@endsection
