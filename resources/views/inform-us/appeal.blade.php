@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мираУ">
@endsection

@section('content')

    {{--  Хлебные крошки --}}
    <nav class="hidden md:block mb-2 mt-3 lg:mt-5 rounded-md mx-auto text-xs sm:text-sm md:text-md px-1">

        @php
            $homeUrl = route('home');
            $entityTypeUrl = route('inform-us.appeal');

            if ($region && $region !== 'russia') {
                $homeUrl = route('home', ['regionTranslit' => $region]);
            }
        @endphp

        <ol class="list-reset flex flex-nowrap overflow-hidden">
            <li class="text-neutral-500">
                <a href="{{ $homeUrl }}" class="truncate">
                    Главная
                </a>
            </li>
            <li>
                <a href="{{ $homeUrl }}">
                    <span class="mx-2 text-neutral-500">/</span>
                </a>
            </li>
            <li class="text-neutral-500">
                <a href="{{ $entityTypeUrl }}" class="truncate">
                    Сообщите нам
                </a>
            </li>

        </ol>
    </nav>

    <section>
        <div class="flex flex-col sm:justify-center items-center py-6">

            @if (session('success'))
                <div class="mt-5 w-full sm:max-w-xl rounded-lg bg-green-100 px-6 py-5 text-base text-green-700"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="w-full sm:max-w-xl my-6 px-6 py-6 bg-white overflow-hidden sm:rounded-lg">

                <h3 class="text-xl font-semibold">Ваше обращение</h3>
                <p class="text-sm">Опишите вашу проблему</p>
                <hr class="mt-4">

                <!-- Session Status -->

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('inform-us.appeal') }}" enctype="multipart/form-data">
                    @csrf

                    @if (session('error'))
                        <x-input-error :messages="session('error')" class="mt-2 mb-3" />
                    @endif

                    <!-- name -->
                    <div class="mt-4">
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" placeholder="Ваше имя" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- phone -->
                    <div class="mt-4">
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                            :value="old('phone')" placeholder="Ваш телефон" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>


                    <!-- message -->
                    <div class="mt-4">
                        <textarea id="message"
                            class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text"
                            name="message" :value="old('message')" placeholder="Сообщение"></textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-center mt-4">

                        <div class="flex items-center justify-end">
                            <x-primary-button class="px-3">
                                {{ __('отправить') }}
                            </x-primary-button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
