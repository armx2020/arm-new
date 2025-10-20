@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Мои общины</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Мои общины">
@endsection

@section('content')
    <div class="flex flex-col lg:flex-row mx-auto my-10">

        @include('profile.menu')

        <div class="flex flex-col basis-full lg:basis-4/5 lg:m-3 my-3 lg:ml-5">
            @if (session('success'))
                <div class="mb-4 flex basis-full bg-green-100 rounded-lg px-6 py-5 text-base text-green-700" role="alert"
                    style="max-height:64px;">
                    {{ session('success') }}
                </div>
            @endif
            <div class="flex flex-col basis-full">
                <div class="flex flex-col md:flex-row basis-full bg-white rounded-md p-1 lg:p-5 relative">
                    <div class="flex flex-col">
                        @if ($entity->image == null)
                            <img class="h-64 w-64 rounded-2xl p-2 flex object-cover" src="{{ url('/image/no_photo.jpg') }}"
                                alt="image" />
                        @else
                            <img class="h-64 w-64 rounded-2xl p-2 flex object-cover"
                                src="{{ asset('storage/' . $entity->image) }}" alt="image">
                        @endif
                        <div class="p-5 w-64">
                            <div class="my-2 flex flex-row">
                                <div class="basis-4/5 text-left text-sm">Заполненость профиля</div>
                                <div class="basis-1/5 text-right text-sm">{{ $fullness }}%</div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-md mb-5">
                                <div class="bg-green-500 h-2 text-gray-50 align-middle p-0.5 text-center text-md font-medium leading-none text-primary-100"
                                    style='width: {{ $fullness }}%'></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col px-3 lg:px-10">
                        <h3 class="text-left text-xl lg:text-2xl mx-4">{{ $entity->name }}</h3>
                        <p class="text-left text-md mx-4 my-1 text-gray-600">{{ $entity->city->name }}
                            {{ $entity->address }}
                        </p>
                        <p class="text-left text-sm mx-4 my-1 text-gray-500 break-all">{{ $entity->description }}</p>

                        @if ($entity->director)
                            <p class="text-left text-sm mx-4 my-1 text-gray-500 break-all">Руководитель:
                                {{ mb_substr($entity->director, 0, 400, 'UTF-8') }}</p>
                        @endif

                        <hr class="mt-3 mb-3">
                        <x-pages.social :entity=$entity />
                    </div>
                    <div class="sm:basis-1/4 flex-initial text-right flex flex-col">
                        <div class="absolute top-[1.6rem] right-[1.6rem]">
                            <a href="{{ route('mycommunities' . '.edit', ['mycommunity' => $entity->id]) }}"
                                class="inline rounded-md p-1 my-1" title="редактировать">
                                <svg class="inline" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                    width="16" height="16" viewBox="0 0 485.219 485.22"
                                    style="enable-background:new 0 0 485.219 485.22;" xml:space="preserve">
                                    <g>
                                        <path
                                            d="M467.476,146.438l-21.445,21.455L317.35,39.23l21.445-21.457c23.689-23.692,62.104-23.692,85.795,0l42.886,42.897   C491.133,84.349,491.133,122.748,467.476,146.438z M167.233,403.748c-5.922,5.922-5.922,15.513,0,21.436   c5.925,5.955,15.521,5.955,21.443,0L424.59,189.335l-21.469-21.457L167.233,403.748z M60,296.54c-5.925,5.927-5.925,15.514,0,21.44   c5.922,5.923,15.518,5.923,21.443,0L317.35,82.113L295.914,60.67L60,296.54z M338.767,103.54L102.881,339.421   c-11.845,11.822-11.815,31.041,0,42.886c11.85,11.846,31.038,11.901,42.914-0.032l235.886-235.837L338.767,103.54z    M145.734,446.572c-7.253-7.262-10.749-16.465-12.05-25.948c-3.083,0.476-6.188,0.919-9.36,0.919   c-16.202,0-31.419-6.333-42.881-17.795c-11.462-11.491-17.77-26.687-17.77-42.887c0-2.954,0.443-5.833,0.859-8.703   c-9.803-1.335-18.864-5.629-25.972-12.737c-0.682-0.677-0.917-1.596-1.538-2.338L0,485.216l147.748-36.986   C147.097,447.637,146.36,447.193,145.734,446.572z" />
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chat --}}
            {{-- 
            TODO - доделать мессенджер
            <div class="flex flex-col basis-full mt-8">
                <div class="flex flex-col md:flex-row basis-full bg-white rounded-md p-2 lg:p-10 relative">
        
                    <div class="container mx-auto flex">
                        <div class="w-1/3 pr-5">
                            @livewire('profile.chat-list-for-entity', ['entityId' => $entity->id])
                        </div>
        
                        <div class="w-2/3">
                            @isset($chat)
                                @livewire('profile.chat-window-for-entity', ['chatId' => $chat->id, 'entityId' => $entity->id])
                            @else
                                <div class="rounded-lg p-8 text-center">
                                    <p>Выберите чат для начала общения...</p>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
