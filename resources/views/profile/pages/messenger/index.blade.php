@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Мои сообщения</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Мои сообщения">
@endsection

@section('content')
    <div class="flex flex-col lg:flex-row mx-auto my-10">
        @include('profile.menu')
        <div class="flex flex-col basis-full lg:basis-4/5 lg:m-3 my-3 lg:ml-5">
            <div class="flex flex-col basis-full">
                <div class="flex flex-col md:flex-row basis-full bg-white rounded-md p-2 lg:p-10 relative">

                    <div class="container mx-auto flex flex-col lg:flex-row">
                        <div class="basis-full lg:basis-1/3 pr-5">
                            @livewire('profile.chat-list')
                        </div>

                        <div class="basis-full lg:basis-2/3">
                            @isset($chat)
                                @livewire('profile.chat-window', ['chatId' => $chat->id])
                            @else
                                <div class="rounded-lg p-8 text-center">
                                    <p>Выберите чат для начала общения...</p>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
