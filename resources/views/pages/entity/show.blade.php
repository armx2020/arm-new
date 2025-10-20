@extends('layouts.app')

@php
    $sitemap = App\Models\SiteMap::where('url', url()->current())->First();

    $title = 'Армянский справочник для армян России и мира';
    $description = 'Армянский справочник для армян России и мира';

    if ($sitemap) {
        $title = $sitemap->title;
        $description = $sitemap->description;
    }

@endphp

@section('title')
    <title>{{ $title }}
    </title>
@endsection

@section('meta')
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $description }}">
@endsection

@section('scripts')
    <link rel="stylesheet" href="{{ url('/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ url('/swiper/fancybox.css') }}">
    <script src="{{ url('/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ url('/swiper/fancybox.umd.js') }}"></script>
@endsection

@section('content')
    {{--  Хлебные крошки --}}
    <nav class="hidden md:block mb-2 mt-3 lg:mt-5 rounded-md mx-auto text-xs sm:text-sm md:text-md px-1">

        @php
            $homeUrl = route('home');
            $entityTypeUrl = route("$entityTranscription.index");

            if ($region && $region !== 'russia') {
                $homeUrl = route('home', ['regionTranslit' => $region]);
                $entityTypeUrl = route("$entityTranscription.region", ['regionTranslit' => $region]);
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
                    {{ $entityName }}
                </a>
            </li>
            <li>
                <a href="{{ $entityTypeUrl }}">
                    <span class="mx-2 text-neutral-500">/</span>
                </a>
            </li>
            <li class="text-neutral-500">
                <a href="" class="truncate">
                    {{ $entity->name }}
                </a>
            </li>
        </ol>
    </nav>

    @if (session('success'))
        <div class="mt-5 w-full rounded-lg bg-green-100 px-6 py-5 text-base text-green-700" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <x-pages.entity-card :$entity :$entityTypeUrl />

    @if (!$otherEntities->isEmpty())
        <x-pages.other-entities :entities="$otherEntities" :$entityShowRoute :$region />
    @endif

    <x-inform-us.index />
@endsection
