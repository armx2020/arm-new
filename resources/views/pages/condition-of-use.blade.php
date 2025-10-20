@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Условия использования</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Условия использования">
@endsection

@section('content')
<nav class="mb-2 mt-5 rounded-md mx-auto px-3 lg:px-2 text-sm md:text-md">
    <ol class="list-reset flex">
        <li>
            <a href="{{ route('home') }}" class="text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700">Главная</a>
        </li>
        <li>
            <span class="mx-2 text-neutral-500">/</span>
        </li>
        <li class="text-neutral-500">
            <a href="{{ route('condition-of-use') }}">
                Условия использования</a>
        </li>
    </ol>
</nav>
<section>
    <div class="flex flex-col mx-auto my-6 lg:my-8">
        <div class="flex basis-full bg-white rounded-md p-3 md:p-6 lg:p-10 ">
            <div class="text-justify">
                <span class="font-bold text-lg">Условия пользования веб-сайта "Армянские Общины по России"</span>
                <br><br><br>
                1. Пользовательское соглашение<br>
                <p class="m-0 md:mx-7 mt-3 text-neutral-600">
                    1.1. Используя наш веб-сайт, вы соглашаетесь с этими условиями пользования. Если вы не согласны с этими условиями, пожалуйста, не используйте наш сайт.
                </p><br>
                2. Ограничения использования<br>
                <p class="m-0 md:mx-7 mt-3 text-neutral-600">
                    2.1. Вы соглашаетесь не использовать сайт для незаконных целей или для распространения вредоносного контента.<br>
                </p><br>
                3. Интеллектуальная собственность<br>
                <p class="m-0 md:mx-7 mt-3 text-neutral-600">
                    3.1. Все материалы, размещенные на сайте, защищены авторскими правами и другими правами интеллектуальной собственности.
                </p><br>
                4. Отказ от ответственности<br>
                <p class="m-0 md:mx-7 mt-3 text-neutral-600">
                    4.1. Мы не несем ответственность за любой ущерб или убытки, возникшие в результате использования нашего сайта.
                </p><br>
                5. Изменения в условиях пользования<br>
                <p class="m-0 md:mx-7 mt-3 text-neutral-600">
                    5.1. Мы оставляем за собой право вносить изменения в наши условия пользования без предварительного уведомления.
                </p><br>
            </div>
        </div>
    </div>
</section>
@endsection