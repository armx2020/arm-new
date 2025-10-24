@extends('admin.layouts.app')

@section('content')
<div class="pt-6 px-4 max-w-7xl mx-auto pb-20">
    <h1 class="text-3xl font-bold">🔧 Диагностика работает!</h1>
    <p class="mt-4">Страница диагностики доступна в Replit.</p>
    <div class="mt-6 bg-gray-100 p-4 rounded">
        <p><strong>Проект:</strong> vsearmyne.ru</p>
        <p><strong>Environment:</strong> {{ config('app.env') }}</p>
        <p><strong>PHP:</strong> {{ phpversion() }}</p>
    </div>
</div>
@endsection
