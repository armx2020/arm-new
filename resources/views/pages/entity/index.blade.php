@extends('layouts.app')

@php
    $sitemap = App\Models\SiteMap::select('url', 'title', 'description')
        ->where('url', url()->current())
        ->First();

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

        </ol>
    </nav>
    <section>
        <div>
            <div class="flex flex-col lg:flex-row mx-auto mt-3 md:mt-0">

                <div class="flex-col text-xs md:text-sm">

                    {{--  Выбор типа сущности --}}
                    <div class="flex flex-col basis-full lg:basis-1/5 max-w-56">
                        <div class="flex flex-row gap-3">
                            <div class="bg-white mt-3 basis-full rounded-md">
                                <select name="type" class="w-full border-0 rounded-md" autocomplete="off"
                                    id="entity-types-select">
                                    @foreach (App\Models\EntityType::active()->get() as $type)
                                        @if (Route::has($type->transcription . '.index'))
                                            @php
                                                $routeNameForEntityTypeSelect = route($type->transcription . '.index');

                                                if ($region && $region !== 'russia') {
                                                    $routeNameForEntityTypeSelect = route(
                                                        "$type->transcription.region",
                                                        ['regionTranslit' => $region],
                                                    );
                                                }
                                            @endphp
                                            <option @selected($type->transcription == $entityTranscription)
                                                value="{{ $routeNameForEntityTypeSelect }}">{{ $type->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#entity-types-select').change(function() {
                                window.location.href = $(this).val();
                            });
                        });
                    </script>

                    <div class="flex flex-row lg:flex-col">

                        {{--  Выбор категории --}}
                        @if (isset($categories) && count($categories) > 0)
                            @php
                                $routeName = route($entityTranscription . '.index');

                                if ($region && $region !== 'russia') {
                                    $routeName = route("$entityTranscription.region", ['regionTranslit' => $region]);
                                }
                            @endphp
                            <div class="flex flex-col basis-1/2 lg:basis-1/5 max-w-56">
                                <div class="flex flex-row gap-3">

                                    {{-- Декстопная версия --}}
                                    <div class="bg-white mt-3 basis-full rounded-md p-3 hidden lg:block">
                                        <h4 class="mb-2 font-semibold text-gray-900">Категория</h4>
                                        <div class="flex items-center mb-2">
                                            <input type="radio" value="{{ $routeName }}" name="category"
                                                @checked(!$selectedCategory)
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                            <label for="all_categories" class="mx-2 text-sm font-medium text-gray-900">
                                                Все</label>
                                        </div>
                                        @foreach ($categories as $category)
                                            <div class="flex items-center mb-2">
                                                <input type="radio"
                                                    value="{{ $routeName . '/' . $category->transcription }}"
                                                    name="category" @checked($selectedCategory && $selectedCategory->id == $category->id)
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="{{ $category->name }}"
                                                    class="mx-2 text-sm font-medium text-gray-900">
                                                    {{ $category->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            $('input[name="category"]').change(function() {
                                                var value = $('input[name="category"]:checked').val();
                                                window.location.href = value;
                                            });
                                        });
                                    </script>

                                    {{-- Мобильная версия --}}
                                    <div class="bg-white mt-3 basis-full rounded-md block lg:hidden">
                                        <select id="category_select" class="w-full border-0 rounded-md" autocomplete="off">
                                            <option value="{{ $routeName }}" @selected(!$selectedCategory)>Все категории
                                            </option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $routeName . '/' . $category->transcription }}"
                                                    @selected($selectedCategory && $selectedCategory->id == $category->id)>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <script>
                                        $(document).ready(function() {
                                            $('#category_select').change(function() {
                                                window.location.href = $(this).val();
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        @endif

                        {{--  Выбор региона --}}
                        <div
                            class="flex flex-col @if (isset($categories) && count($categories) > 0) basis-1/2 pl-1 md:pl-0 @else basis-full @endif lg:basis-1/5 max-w-56">
                            <div class="flex flex-row gap-3">
                                <div class="bg-white mt-3 basis-full rounded-md">
                                    <select name="region" id="region-select" class="w-full border-0 rounded-md"
                                        autocomplete="off">

                                        @php
                                            if (
                                                isset(request()->route()->parameters['regionTranslit']) &&
                                                mb_strlen(request()->route()->parameters['regionTranslit']) == 2
                                            ) {
                                                $regionsCollect = collect(Cache::get('all_countries', []));
                                                $isCountry = true;
                                            } else {
                                                $regionsCollect = collect(Cache::get('all_regions', []));
                                                $isCountry = false;
                                            }

                                            if ($regionsCollect->isEmpty()) {
                                                $regionsCollect = App\Models\Region::all();
                                            }
                                        @endphp

                                        @if ($isCountry)
                                            @foreach ($regionsCollect as $reg)
                                                @php
                                                    $routeNameForRegionSelect = route("$entityTranscription.region", [
                                                        'regionTranslit' => $reg->code,
                                                    ]);

                                                    if ($selectedCategory) {
                                                        $routeNameForRegionSelect .=
                                                            '/' . $selectedCategory->transcription;
                                                    }

                                                    if ($selectedSubCategory) {
                                                        $routeNameForRegionSelect .=
                                                            '/' . $selectedSubCategory->transcription;
                                                    }

                                                    $displayName = $reg->name_ru;

                                                @endphp

                                                <option @selected($reg->code == request()->route()->parameters['regionTranslit'])
                                                    value="{{ $routeNameForRegionSelect }}">
                                                    {{ $displayName }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($regionsCollect as $reg)
                                                @php
                                                    $routeNameForRegionSelect = route("$entityTranscription.region", [
                                                        'regionTranslit' => $reg->transcription,
                                                    ]);

                                                    if ($selectedCategory) {
                                                        $routeNameForRegionSelect .=
                                                            '/' . $selectedCategory->transcription;
                                                    }

                                                    if ($selectedSubCategory) {
                                                        $routeNameForRegionSelect .=
                                                            '/' . $selectedSubCategory->transcription;
                                                    }

                                                    // Проверяем, если регион "Россия" — подставляем другое название для отображения
                                                    $displayName = $reg->name === 'Россия' ? 'Все регионы' : $reg->name;
                                                @endphp

                                                <option @selected($reg->transcription == $region)
                                                    value="{{ $routeNameForRegionSelect }}">
                                                    {{ $displayName }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#region-select').change(function() {
                                    window.location.href = $(this).val();
                                });
                            });
                        </script>

                    </div>

                    {{--  Выбор подкатегории --}}
                    @if ($subCategories && count($subCategories) > 0)
                        @php
                            if ($selectedCategory) {
                                $routeName = $routeName . '/' . $selectedCategory->transcription;
                            }
                        @endphp
                        <div class="flex flex-col basis-1/2 lg:basis-1/5 max-w-56 pl-1 md:pl-0">
                            <div class="flex flex-row gap-3">

                                <div class="bg-white mt-3 basis-full rounded-md p-3 hidden lg:block">
                                    <h4 class="mb-2 font-semibold text-gray-900">Специализация</h4>
                                    <div class="flex items-center mb-2">
                                        <input type="radio" value="{{ $routeName }}" @checked(!$selectedSubCategory)
                                            name="subCategory"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        <label for="all_categories" class="mx-2 text-sm font-medium text-gray-900">
                                            Все</label>
                                    </div>
                                    @foreach ($subCategories as $category)
                                        <div class="flex items-center mb-2">
                                            <input type="radio" value="{{ $routeName . '/' . $category->transcription }}"
                                                name="subCategory" @checked($selectedSubCategory && $selectedSubCategory->id == $category->id)
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                            <label for="{{ $category->name }}"
                                                class="mx-2 text-sm font-medium text-gray-900">
                                                {{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('input[name="subCategory"]').change(function() {
                                            var value = $('input[name="subCategory"]:checked').val();
                                            window.location.href = value;
                                        });
                                    });
                                </script>

                                @if ($selectedCategory->id == '19' || $selectedCategory->id == '78')
                                    <div class="bg-white mt-3 basis-full rounded-md block lg:hidden">
                                        <select id="sub_category_select" class="w-full border-0 rounded-md"
                                            autocomplete="off">
                                            <option value="{{ $routeName }}" @selected(!$selectedSubCategory)>Все
                                                специализации</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $routeName . '/' . $category->transcription }}"
                                                    @selected($selectedSubCategory && $selectedSubCategory->id == $category->id)>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            $('#sub_category_select').change(function() {
                                                window.location.href = $(this).val();
                                            });
                                        });
                                    </script>
                                @endif

                            </div>
                        </div>
                    @endif

                </div>


                <div class="flex flex-col basis-full lg:basis-4/5 lg:m-3 my-5 lg:ml-5 min-h-screen max-w-5xl">

                    {{-- @if ($entityName == 'Интересные места, церкви' && isset($lat) && isset($lon))
                        <x-pages.map :$lat :$lon :entities="$entities" :region="$region"/>
                    @endif --}}

                    <div class="w-full">
                        @if ($entities->isEmpty())
                            <x-pages.absence-entity />
                        @else
                            <x-pages.grid :entities="$entities" :$entityShowRoute :$region />
                            <div class="w-full  mx-auto py-3 lg:py-10">
                                {{ $entities->onEachSide(2)->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>



        </div>
        <x-inform-us.index />
    </section>
@endsection

@section('body')
@endsection
