@extends('admin.layouts.app')

@section('content')

<div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto pb-20">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">🔧 Диагностика системы</h1>
        <p class="text-gray-600 mt-2">Информация о структуре проекта, состоянии систем и статистика</p>
    </div>

    @if(!empty(getenv('REPLIT_DEV_DOMAIN')) || !empty(getenv('REPLIT_DOMAINS')))
        @php
            $currentMode = session('db_mode', 'demo');
            $isDemoMode = $currentMode === 'demo';
            $currentConnection = config('database.default');
        @endphp
        
        <div class="bg-gradient-to-r {{ $isDemoMode ? 'from-green-500 to-blue-500' : 'from-red-600 to-orange-600' }} shadow-xl rounded-lg p-6 mb-6 text-white">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-2">
                        @if($isDemoMode)
                            🚀 Режим: ДЕМО (PostgreSQL)
                        @else
                            🔴 Режим: БОЕВОЙ (MySQL)
                        @endif
                    </h2>
                    <div class="space-y-1 text-sm">
                        <p class="opacity-90">
                            <strong>База данных:</strong> {{ $currentConnection }} 
                            @if($isDemoMode)
                                (PostgreSQL Neon, США - быстро ~0.8s)
                            @else
                                (MySQL Timeweb, Россия - медленно ~2-3s)
                            @endif
                        </p>
                        <p class="opacity-90">
                            <strong>Данные:</strong> 
                            @if($isDemoMode)
                                Демо-данные для разработки (5 сущностей, 5 категорий)
                            @else
                                Боевые данные из России (полная база)
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <form method="POST" action="{{ route('replit.db.switch') }}">
                        @csrf
                        <input type="hidden" name="mode" value="{{ $isDemoMode ? 'production' : 'demo' }}">
                        <button type="submit" class="bg-white {{ $isDemoMode ? 'text-blue-600 hover:bg-blue-50' : 'text-red-600 hover:bg-red-50' }} font-bold px-8 py-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center gap-3 text-lg">
                            @if($isDemoMode)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>Переключить на MySQL<br><small class="text-xs opacity-70">(медленно)</small></span>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span>Переключить на PostgreSQL<br><small class="text-xs opacity-70">(быстро)</small></span>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white border-opacity-30">
                <p class="text-xs opacity-75">
                    💡 <strong>Подсказка:</strong> 
                    @if($isDemoMode)
                        Демо режим использует PostgreSQL (США) для быстрой разработки. Переключитесь на боевой режим для доступа к полной базе MySQL из России.
                    @else
                        Боевой режим подключается к MySQL в России (медленно ~2-3s). Для быстрой разработки переключитесь на демо режим с PostgreSQL (~0.8s).
                    @endif
                </p>
            </div>
        </div>
    @endif

    {{-- Информация о проекте --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">📋 Информация о проекте</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Название</p>
                <p class="font-semibold">{{ $projectInfo['name'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Версия Laravel</p>
                <p class="font-semibold">{{ $projectInfo['version'] }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Описание</p>
                <p class="font-semibold">{{ $projectInfo['description'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">PHP версия</p>
                <p class="font-semibold">{{ $projectInfo['php_version'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Git ветка</p>
                <p class="font-semibold">{{ $projectInfo['git_branch'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Git коммит</p>
                <p class="font-semibold">{{ $projectInfo['git_commit'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Дата последнего коммита</p>
                <p class="font-semibold">{{ $projectInfo['last_commit_date'] }}</p>
            </div>
        </div>
    </div>

    {{-- Схема проекта --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🏗️ Схема проекта</h2>
        <div class="bg-gray-50 p-4 rounded-lg font-mono text-sm overflow-x-auto">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <span class="text-blue-600 font-bold">Replit (Разработка)</span>
                    <span>→</span>
                    <span class="text-green-600 font-bold">GitHub (armx2020/arm-new)</span>
                    <span>→</span>
                    <span class="text-purple-600 font-bold">Timeweb Staging</span>
                    <span>→</span>
                    <span class="text-red-600 font-bold">Production (vsearmyane.ru)</span>
                </div>
                <div class="border-l-4 border-gray-300 pl-4 mt-4 space-y-1">
                    <p><strong>Replit:</strong> {{ config('app.url') }}</p>
                    <p><strong>Staging:</strong> {{ $environmentInfo['staging_url'] }}</p>
                    <p><strong>Production:</strong> {{ $environmentInfo['production_url'] }}</p>
                    <p><strong>GitHub:</strong> {{ $environmentInfo['github_repo'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Статус баз данных --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🗄️ Статус баз данных</h2>
        <div class="space-y-4">
            @foreach($databaseStatus as $dbName => $db)
                <div class="border rounded-lg p-4 {{ $db['status'] === 'connected' ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-lg">
                                @if($dbName === 'postgresql')
                                    PostgreSQL (Replit Development)
                                @elseif($dbName === 'mysql_dev')
                                    MySQL Dev (Timeweb armbase-2)
                                @else
                                    MySQL Production (Read-Only)
                                @endif
                            </h3>
                            @if($db['status'] === 'connected')
                                <p class="text-sm text-gray-600">Host: {{ $db['host'] ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Database: {{ $db['database'] ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Таблиц: {{ $db['tables_count'] ?? 0 }}</p>
                            @else
                                <p class="text-sm text-red-600">Ошибка: {{ $db['error'] ?? 'Unknown error' }}</p>
                            @endif
                        </div>
                        <div>
                            @if($db['status'] === 'connected')
                                <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm font-semibold">✓ Подключено</span>
                            @else
                                <span class="px-3 py-1 bg-red-600 text-white rounded-full text-sm font-semibold">✗ Ошибка</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Статус S3 --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">☁️ Статус S3 Cloud Storage</h2>
        <div class="border rounded-lg p-4 {{ $s3Status['status'] === 'connected' ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">Timeweb S3</h3>
                    @if($s3Status['status'] === 'connected')
                        <p class="text-sm text-gray-600">Bucket: {{ $s3Status['bucket'] ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Endpoint: {{ $s3Status['endpoint'] ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Region: {{ $s3Status['region'] ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Default Disk: {{ $s3Status['default_disk'] ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 font-semibold mt-2">Файлов: {{ $environmentInfo['s3_images_count'] }}</p>
                        <p class="text-sm text-gray-600 font-semibold">Размер: {{ $environmentInfo['s3_storage_size'] }}</p>
                    @else
                        <p class="text-sm text-red-600">Ошибка: {{ $s3Status['error'] ?? 'Unknown error' }}</p>
                    @endif
                </div>
                <div>
                    @if($s3Status['status'] === 'connected')
                        <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm font-semibold">✓ Подключено</span>
                    @else
                        <span class="px-3 py-1 bg-red-600 text-white rounded-full text-sm font-semibold">✗ Ошибка</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Статус системы --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">⚙️ Статус системы</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Окружение</p>
                <p class="font-semibold">{{ $systemStatus['app_env'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Debug режим</p>
                <p class="font-semibold">{{ $systemStatus['app_debug'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">APP URL</p>
                <p class="font-semibold truncate">{{ $systemStatus['app_url'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Cache Driver</p>
                <p class="font-semibold">{{ $systemStatus['cache_driver'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Session Driver</p>
                <p class="font-semibold">{{ $systemStatus['session_driver'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Queue Driver</p>
                <p class="font-semibold">{{ $systemStatus['queue_driver'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Disk Space (Total)</p>
                <p class="font-semibold">{{ $systemStatus['disk_space_total'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Disk Space (Free)</p>
                <p class="font-semibold">{{ $systemStatus['disk_space_free'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Memory Limit</p>
                <p class="font-semibold">{{ $systemStatus['memory_limit'] }}</p>
            </div>
        </div>
    </div>

    {{-- Статистика --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">📊 Статистика</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Пользователи --}}
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg mb-3">👥 Пользователи</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Всего:</span>
                        <span class="font-bold">{{ number_format($statistics['users']['total']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Сегодня:</span>
                        <span class="font-bold text-green-600">{{ $statistics['users']['today'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">За неделю:</span>
                        <span class="font-bold">{{ $statistics['users']['this_week'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">За месяц:</span>
                        <span class="font-bold">{{ $statistics['users']['this_month'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Сущности --}}
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg mb-3">🏢 Сущности</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Всего:</span>
                        <span class="font-bold">{{ number_format($statistics['entities']['total']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Компаний:</span>
                        <span class="font-bold">{{ number_format($statistics['entities']['companies']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Групп:</span>
                        <span class="font-bold">{{ number_format($statistics['entities']['groups']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Мест:</span>
                        <span class="font-bold">{{ number_format($statistics['entities']['places']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Изображения --}}
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg mb-3">🖼️ Изображения</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Всего:</span>
                        <span class="font-bold">{{ number_format($statistics['images']['total']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Непроверено:</span>
                        <span class="font-bold text-orange-600">{{ $statistics['images']['unchecked'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Категории --}}
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg mb-3">📁 Категории</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Всего:</span>
                        <span class="font-bold">{{ number_format($statistics['categories']['total']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Сообщения --}}
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg mb-3">✉️ Сообщения</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Всего:</span>
                        <span class="font-bold">{{ number_format($statistics['appeals']['total']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Активных:</span>
                        <span class="font-bold text-blue-600">{{ $statistics['appeals']['active'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Предложения --}}
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg mb-3">🎁 Предложения</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Всего:</span>
                        <span class="font-bold">{{ number_format($statistics['offers']['total']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Активных:</span>
                        <span class="font-bold text-green-600">{{ $statistics['offers']['active'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Deployment Flow --}}
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">🚀 Deployment Flow</h2>
        <div class="space-y-3">
            <p class="text-gray-700"><strong>1. Разработка на Replit:</strong> Внесение изменений и тестирование</p>
            <p class="text-gray-700"><strong>2. Push в GitHub:</strong> Автоматическая синхронизация через Git UI</p>
            <p class="text-gray-700"><strong>3. Auto-Deploy на Staging:</strong> Webhook Timeweb автоматически разворачивает код</p>
            <p class="text-gray-700"><strong>4. Production:</strong> Ручной деплой после проверки на staging</p>
            <div class="mt-4 p-4 bg-white rounded-lg border-l-4 border-blue-500">
                <p class="text-sm text-gray-600 font-mono">{{ $environmentInfo['deployment_flow'] }}</p>
            </div>
        </div>
    </div>

</div>

@endsection
