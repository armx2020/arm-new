@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Мой профиль </title>
@endsection

@section('meta')
    <meta name="robots" content="index, follow" />
    <meta name="description" content="Армянский справочник для армян России и мира">
@endsection


@section('content')
    <div class="flex flex-col lg:flex-row mx-auto my-10">

        @include('profile.menu')

        <div class="flex basis-full lg:basis-4/5 lg:m-3 my-3 lg:ml-5">
            <div class="flex flex-col md:flex-row basis-full bg-white rounded-md p-2 lg:p-6">
                <div class="flex flex-col basis-1/4">
                    @if (Auth::user()->image)
                        <img class="h-40 lg:h-48 w-40 lg:w-48 rounded-full mx-auto p-1 flex object-cover"
                            src="{{ storage_url(Auth::user()->image) }}" alt="{{ Auth::user()->firstname }}">
                    @else
                        <img class="h-40 lg:h-48 w-40 lg:w-48 rounded-full mx-auto p-1 flex object-cover"
                            src="{{ url('/image/no-image.png') }}" alt="image" />
                    @endif
                    <div class="m-5">
                        <div class="my-2 flex flex-row">
                            <div class="basis-4/5 text-left text-sm">Заполненость профиля</div>
                            <div class="basis-1/5 text-right text-sm">{{ $fullness }}%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-sm mb-5">
                            <div class="bg-green-500 h-2 rounded-l-sm text-gray-50 align-middle p-0.5 text-center text-md font-medium leading-none text-primary-100"
                                style='width: {{ $fullness }}%'></div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col px-3 lg:px-10 basis-3/4">
                    <h3 class="text-left text-xl lg:text-2xl mx-4 mt-2">{{ Auth::user()->firstname }}
                        {{ Auth::user()->lastname }}</h1>
                        <p class="text-left text-md mx-4 my-1 text-gray-600">{{ Auth::user()->city->name }}
                            ({{ Auth::user()->region->name }})</p>

                        <hr class="mt-3 mb-3">
                        <div class="mx-4">
                            <x-pages.social :entity=Auth::user() />
                        </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
