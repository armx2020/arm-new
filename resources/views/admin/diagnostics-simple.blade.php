@extends('admin.layouts.app')

@section('content')

<div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto pb-20">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">🔧 Диагностика системы</h1>
        <p class="text-gray-600 mt-2">Информация о проекте и окружении</p>
    </div>

    {{-- Информация о проекте --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">📋 Информация о проекте</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Название</p>
                <p class="font-semibold">{{ $projectInfo['name'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Версия</p>
                <p class="font-semibold">{{ $projectInfo['version'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">PHP Версия</p>
                <p class="font-semibold">{{ $projectInfo['php_version'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Git Branch</p>
                <p class="font-semibold">{{ $projectInfo['git_branch'] }}</p>
            </div>
        </div>
    </div>

    {{-- Окружение --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🌍 Окружение</h2>
        <div class="space-y-2">
            <p><strong>App Environment:</strong> {{ $systemStatus['app_env'] }}</p>
            <p><strong>App URL:</strong> {{ $systemStatus['app_url'] }}</p>
            <p><strong>Debug:</strong> {{ $systemStatus['app_debug'] }}</p>
        </div>
    </div>

    {{-- Deployment Flow --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🚀 Deployment Flow</h2>
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="space-y-2 font-mono text-sm">
                <p>{{ $environmentInfo['deployment_flow'] }}</p>
                <div class="mt-4 space-y-1">
                    <p><strong>Replit:</strong> {{ $systemStatus['app_url'] }}</p>
                    <p><strong>Staging:</strong> {{ $environmentInfo['staging_url'] }}</p>
                    <p><strong>Production:</strong> {{ $environmentInfo['production_url'] }}</p>
                    <p><strong>GitHub:</strong> {{ $environmentInfo['github_repo'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- S3 Storage --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">☁️ S3 Cloud Storage</h2>
        <div class="space-y-2">
            <p><strong>Bucket:</strong> {{ $s3Status['bucket'] }}</p>
            <p><strong>Endpoint:</strong> {{ $s3Status['endpoint'] }}</p>
            <p><strong>Файлов:</strong> {{ $environmentInfo['s3_images_count'] }}</p>
            <p><strong>Размер:</strong> {{ $environmentInfo['s3_storage_size'] }}</p>
        </div>
    </div>

    {{-- База данных --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🗄️ База данных</h2>
        <p class="text-gray-600">Текущее подключение: <strong>{{ $databaseStatus['test']['database'] ?? 'N/A' }}</strong></p>
        <p class="text-gray-600 mt-2">Host: <strong>{{ $databaseStatus['test']['host'] ?? 'N/A' }}</strong></p>
    </div>

</div>

@endsection
