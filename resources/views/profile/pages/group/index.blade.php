@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Мои сообщества</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Мои сообщества">
@endsection

@section('content')
    <div class="flex flex-col lg:flex-row mx-auto my-10">
        @include('profile.menu')
        <div class="flex flex-col basis-full lg:basis-4/5 lg:m-3 my-3 lg:ml-5">
            <x-profile.alert />
            <x-profile.grid :$entities :$entityName :$entitiesName />
        </div>
    </div>
@endsection
