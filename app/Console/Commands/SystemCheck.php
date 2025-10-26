<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Entity;
use App\Models\Image;

class SystemCheck extends Command
{
    protected $signature = 'system:check {--detailed : Показать детальную информацию}';
    protected $description = 'Комплексная проверка работоспособности всех систем';

    private $errors = [];
    private $warnings = [];
    private $passed = [];

    public function handle()
    {
        $this->info('🔍 Начинаю комплексную проверку систем...');
        $this->newLine();

        $this->checkDatabase();
        $this->checkS3Storage();
        $this->checkFileSystem();
        $this->checkCache();
        $this->checkModels();
        $this->checkRoutes();
        $this->checkConfig();
        $this->checkPerformance();
        $this->checkSecurity();

        $this->newLine();
        $this->displayResults();

        return empty($this->errors) ? 0 : 1;
    }

    private function checkDatabase()
    {
        $this->info('📊 Проверка базы данных...');

        try {
            DB::connection()->getPdo();
            $this->passed[] = 'Подключение к БД успешно';

            $usersCount = User::count();
            $entitiesCount = Entity::count();
            $imagesCount = Image::count();

            $this->passed[] = "Пользователей: {$usersCount}";
            $this->passed[] = "Компаний/мест: {$entitiesCount}";
            $this->passed[] = "Изображений: {$imagesCount}";

            $tables = DB::select('SHOW TABLES');
            $this->passed[] = 'Таблиц в БД: ' . count($tables);

            if (DB::getDriverName() === 'mysql') {
                $version = DB::select('SELECT VERSION() as version')[0]->version;
                $this->passed[] = "MySQL версия: {$version}";
            }

        } catch (\Exception $e) {
            $this->errors[] = 'Ошибка БД: ' . $e->getMessage();
        }
    }

    private function checkS3Storage()
    {
        $this->info('☁️  Проверка S3 хранилища...');

        try {
            $disk = Storage::disk('s3');
            
            $testFile = 'test-' . time() . '.txt';
            $disk->put($testFile, 'test content');
            
            if ($disk->exists($testFile)) {
                $this->passed[] = 'S3: Запись файлов работает';
                
                $content = $disk->get($testFile);
                if ($content === 'test content') {
                    $this->passed[] = 'S3: Чтение файлов работает';
                }
                
                $disk->delete($testFile);
                $this->passed[] = 'S3: Удаление файлов работает';
            }

            $files = $disk->allFiles();
            $this->passed[] = 'Файлов на S3: ' . count($files);

        } catch (\Exception $e) {
            $this->errors[] = 'Ошибка S3: ' . $e->getMessage();
        }
    }

    private function checkFileSystem()
    {
        $this->info('📁 Проверка файловой системы...');

        $paths = [
            storage_path('app'),
            storage_path('logs'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
        ];

        foreach ($paths as $path) {
            if (is_writable($path)) {
                $this->passed[] = "Доступ на запись: {$path}";
            } else {
                $this->errors[] = "Нет доступа на запись: {$path}";
            }
        }

        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $size = filesize($logFile);
            $sizeMB = round($size / 1024 / 1024, 2);
            
            if ($sizeMB > 100) {
                $this->warnings[] = "Лог файл большой: {$sizeMB} MB (рекомендуется очистить)";
            } else {
                $this->passed[] = "Размер лога: {$sizeMB} MB";
            }
        }
    }

    private function checkCache()
    {
        $this->info('⚡ Проверка кеширования...');

        try {
            $key = 'system_check_' . time();
            $value = 'test_value';
            
            Cache::put($key, $value, 60);
            
            if (Cache::has($key)) {
                $this->passed[] = 'Cache: Запись работает';
                
                if (Cache::get($key) === $value) {
                    $this->passed[] = 'Cache: Чтение работает';
                }
                
                Cache::forget($key);
            }

            if (config('cache.default') === 'file') {
                $this->warnings[] = 'Используется файловый кеш (для production рекомендуется Redis)';
            } else {
                $this->passed[] = 'Кеш драйвер: ' . config('cache.default');
            }

        } catch (\Exception $e) {
            $this->errors[] = 'Ошибка кеша: ' . $e->getMessage();
        }
    }

    private function checkModels()
    {
        $this->info('🗂️  Проверка моделей...');

        try {
            $latestUser = User::latest()->first();
            if ($latestUser) {
                $this->passed[] = 'Model User работает';
            }

            $latestEntity = Entity::latest()->first();
            if ($latestEntity) {
                $this->passed[] = 'Model Entity работает';
            }

            $latestImage = Image::latest()->first();
            if ($latestImage) {
                $this->passed[] = 'Model Image работает';
            }

        } catch (\Exception $e) {
            $this->errors[] = 'Ошибка моделей: ' . $e->getMessage();
        }
    }

    private function checkRoutes()
    {
        $this->info('🛣️  Проверка маршрутов...');

        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $this->passed[] = 'Зарегистрировано маршрутов: ' . count($routes);

        $criticalRoutes = [
            'home',
            'profile.edit',
            'admin.diagnostics',
        ];

        foreach ($criticalRoutes as $routeName) {
            if (\Illuminate\Support\Facades\Route::has($routeName)) {
                $this->passed[] = "Маршрут '{$routeName}' существует";
            } else {
                $this->warnings[] = "Маршрут '{$routeName}' не найден";
            }
        }
    }

    private function checkConfig()
    {
        $this->info('⚙️  Проверка конфигурации...');

        if (config('app.debug')) {
            $this->warnings[] = 'DEBUG режим включен (отключите на production!)';
        } else {
            $this->passed[] = 'DEBUG режим выключен';
        }

        if (config('app.env') === 'production') {
            $this->passed[] = 'Окружение: PRODUCTION';
        } else {
            $this->passed[] = 'Окружение: ' . config('app.env');
        }

        if (empty(config('app.key'))) {
            $this->errors[] = 'APP_KEY не установлен!';
        } else {
            $this->passed[] = 'APP_KEY установлен';
        }

        $requiredEnvVars = [
            'DB_HOST',
            'DB_DATABASE',
            'AWS_BUCKET',
            'S3_ACCESS_KEY',
        ];

        foreach ($requiredEnvVars as $var) {
            if (empty(env($var))) {
                $this->errors[] = "Переменная окружения {$var} не установлена";
            } else {
                $this->passed[] = "ENV: {$var} установлен";
            }
        }
    }

    private function checkPerformance()
    {
        $this->info('🚀 Проверка производительности...');

        if (function_exists('opcache_get_status')) {
            $status = opcache_get_status();
            if ($status && $status['opcache_enabled']) {
                $this->passed[] = 'OPcache включен';
            } else {
                $this->warnings[] = 'OPcache выключен (влияет на скорость)';
            }
        }

        if (extension_loaded('gd')) {
            $this->passed[] = 'GD расширение установлено';
        } else {
            $this->errors[] = 'GD расширение отсутствует (нужно для изображений)';
        }

        $memoryLimit = ini_get('memory_limit');
        $this->passed[] = "PHP Memory Limit: {$memoryLimit}";

        $maxExecutionTime = ini_get('max_execution_time');
        if ($maxExecutionTime < 60 && $maxExecutionTime != 0) {
            $this->warnings[] = "Малое время выполнения: {$maxExecutionTime}s";
        }
    }

    private function checkSecurity()
    {
        $this->info('🔒 Проверка безопасности...');

        if (config('app.url') && str_starts_with(config('app.url'), 'https://')) {
            $this->passed[] = 'HTTPS настроен';
        } else {
            $this->warnings[] = 'HTTPS не настроен (рекомендуется для production)';
        }

        if (config('session.secure')) {
            $this->passed[] = 'Secure cookies включены';
        } else {
            $this->warnings[] = 'Secure cookies выключены';
        }

        $storagePublic = storage_path('app/public');
        if (is_link(public_path('storage'))) {
            $this->passed[] = 'Symbolic link storage создан';
        } else {
            $this->warnings[] = 'Symbolic link отсутствует (запустите: php artisan storage:link)';
        }
    }

    private function displayResults()
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('                    📊 РЕЗУЛЬТАТЫ ПРОВЕРКИ');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();

        if (!empty($this->passed)) {
            $this->info('✅ УСПЕШНО (' . count($this->passed) . '):');
            foreach ($this->passed as $item) {
                $this->line('  ✓ ' . $item);
            }
            $this->newLine();
        }

        if (!empty($this->warnings)) {
            $this->warn('⚠️  ПРЕДУПРЕЖДЕНИЯ (' . count($this->warnings) . '):');
            foreach ($this->warnings as $item) {
                $this->line('  ⚠ ' . $item);
            }
            $this->newLine();
        }

        if (!empty($this->errors)) {
            $this->error('❌ ОШИБКИ (' . count($this->errors) . '):');
            foreach ($this->errors as $item) {
                $this->line('  ✗ ' . $item);
            }
            $this->newLine();
        }

        $total = count($this->passed) + count($this->warnings) + count($this->errors);
        $score = round((count($this->passed) / $total) * 100);

        $this->info('═══════════════════════════════════════════════════════════');
        $this->info("Общая оценка: {$score}%");
        
        if ($score >= 90) {
            $this->info('🎉 Отлично! Все системы работают нормально.');
        } elseif ($score >= 70) {
            $this->warn('⚠️  Хорошо, но есть предупреждения.');
        } else {
            $this->error('❌ Требуется внимание! Обнаружены проблемы.');
        }
        
        $this->info('═══════════════════════════════════════════════════════════');
    }
}
