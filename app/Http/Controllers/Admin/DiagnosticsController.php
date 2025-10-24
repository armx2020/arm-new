<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\User;
use App\Models\Category;
use App\Models\Image;
use App\Models\Appeal;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class DiagnosticsController extends Controller
{
    public function index()
    {
        // Доступ только в development окружении (Replit)
        if (!app()->environment(['local', 'development'])) {
            abort(404, 'Страница доступна только в development окружении');
        }

        $data = [
            'project_info' => $this->getProjectInfo(),
            'database_status' => $this->getCurrentDatabaseStatus(),
            's3_status' => $this->getS3StatusLight(),
            'system_status' => $this->getSystemStatus(),
            'statistics' => $this->getStatisticsLight(),
            'environment_info' => $this->getEnvironmentInfo(),
            'project_structure' => $this->getProjectStructure(),
        ];

        return view('admin.diagnostics-raw', $data);
    }

    private function getProjectInfo()
    {
        return [
            'name' => 'vsearmyne.ru',
            'description' => 'Армянский справочник - платформа для поиска компаний, групп, мест и вакансий для армянской общины по всему миру',
            'version' => 'Laravel 10',
            'php_version' => PHP_VERSION,
            'git_branch' => trim(shell_exec('git rev-parse --abbrev-ref HEAD 2>/dev/null') ?: 'unknown'),
            'git_commit' => trim(shell_exec('git rev-parse --short HEAD 2>/dev/null') ?: 'unknown'),
            'last_commit_date' => trim(shell_exec('git log -1 --format=%cd --date=format:"%Y-%m-%d %H:%M:%S" 2>/dev/null') ?: 'unknown'),
        ];
    }

    private function getCurrentDatabaseStatus()
    {
        $currentDb = config('database.default');
        $connection = config('database.connections.' . $currentDb);
        
        $status = [
            'connection_name' => $currentDb,
            'driver' => $connection['driver'] ?? 'unknown',
            'host' => $connection['host'] ?? 'unknown',
            'port' => $connection['port'] ?? 'unknown',
            'database' => $connection['database'] ?? 'unknown',
            'username' => $connection['username'] ?? 'unknown',
            'status' => 'disconnected',
            'tables_count' => 0,
            'error' => null,
        ];

        try {
            // Проверка подключения
            DB::connection($currentDb)->getPdo();
            $status['status'] = 'connected';
            
            // Подсчет таблиц
            if ($connection['driver'] === 'mysql') {
                $tables = DB::connection($currentDb)->select('SHOW TABLES');
                $status['tables_count'] = count($tables);
            } elseif ($connection['driver'] === 'pgsql') {
                $tables = DB::connection($currentDb)->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                $status['tables_count'] = count($tables);
            }
        } catch (\Exception $e) {
            $status['status'] = 'error';
            $status['error'] = $e->getMessage();
        }

        return $status;
    }

    private function getDatabaseStatus()
    {
        $status = [];
        
        try {
            // PostgreSQL (Development)
            config(['database.default' => 'pgsql']);
            DB::connection('pgsql')->getPdo();
            $status['postgresql'] = [
                'status' => 'connected',
                'host' => config('database.connections.pgsql.host'),
                'database' => config('database.connections.pgsql.database'),
                'tables_count' => count(DB::connection('pgsql')->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'")),
            ];
        } catch (\Exception $e) {
            $status['postgresql'] = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }

        try {
            // MySQL Dev
            DB::connection('mysql_dev')->getPdo();
            $status['mysql_dev'] = [
                'status' => 'connected',
                'host' => config('database.connections.mysql_dev.host'),
                'database' => config('database.connections.mysql_dev.database'),
                'tables_count' => count(DB::connection('mysql_dev')->select('SHOW TABLES')),
            ];
        } catch (\Exception $e) {
            $status['mysql_dev'] = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }

        try {
            // MySQL Production (read-only)
            DB::connection('mysql')->getPdo();
            $status['mysql_production'] = [
                'status' => 'connected',
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
                'tables_count' => count(DB::connection('mysql')->select('SHOW TABLES')),
            ];
        } catch (\Exception $e) {
            $status['mysql_production'] = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }

        return $status;
    }

    private function getS3StatusLight()
    {
        return [
            'status' => 'unknown',
            'bucket' => config('filesystems.disks.s3.bucket'),
            'endpoint' => config('filesystems.disks.s3.endpoint'),
            'region' => config('filesystems.disks.s3.region'),
            'default_disk' => config('filesystems.default'),
            'note' => 'Легкая версия (без проверки подключения)',
        ];
    }

    private function getS3Status()
    {
        $status = [];
        
        try {
            $disk = Storage::disk('s3');
            
            // Проверка доступности S3
            $testFile = 'diagnostics_test_' . time() . '.txt';
            $disk->put($testFile, 'test');
            $exists = $disk->exists($testFile);
            $disk->delete($testFile);
            
            $status = [
                'status' => $exists ? 'connected' : 'error',
                'bucket' => config('filesystems.disks.s3.bucket'),
                'endpoint' => config('filesystems.disks.s3.endpoint'),
                'region' => config('filesystems.disks.s3.region'),
                'default_disk' => config('filesystems.default'),
            ];
        } catch (\Exception $e) {
            $status = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }

        return $status;
    }

    private function getSystemStatus()
    {
        return [
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'enabled' : 'disabled',
            'app_url' => config('app.url'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'disk_space_total' => $this->formatBytes(disk_total_space('/')),
            'disk_space_free' => $this->formatBytes(disk_free_space('/')),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];
    }

    private function getStatisticsLight()
    {
        return [
            'message' => 'Статистика отключена для ускорения загрузки страницы',
            'note' => 'БД запросы могут быть медленными в Replit',
        ];
    }

    private function getStatistics()
    {
        return [
            'users' => [
                'total' => User::count(),
                'today' => User::whereDate('created_at', today())->count(),
                'this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'entities' => [
                'total' => Entity::count(),
                'companies' => Entity::companies()->count(),
                'groups' => Entity::groups()->count(),
                'places' => Entity::places()->count(),
                'today' => Entity::whereDate('created_at', today())->count(),
            ],
            'categories' => [
                'total' => Category::count(),
            ],
            'images' => [
                'total' => Image::count(),
                'unchecked' => Image::where('checked', false)->count(),
            ],
            'appeals' => [
                'total' => Appeal::count(),
                'active' => Appeal::active()->count(),
            ],
            'offers' => [
                'total' => Offer::count(),
                'active' => Offer::where('date_end', '>=', now())->count(),
            ],
        ];
    }

    private function getEnvironmentInfo()
    {
        return [
            'production_url' => 'https://vsearmyane.ru',
            'staging_url' => 'https://armx2020-arm-new-d635.twc1.net',
            'github_repo' => 'https://github.com/armx2020/arm-new',
            'deployment_flow' => 'Replit → GitHub → Timeweb Auto-Deploy',
            's3_images_count' => '20,781 файлов',
            's3_storage_size' => '4.64 GB',
        ];
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    private function getProjectStructure()
    {
        return [
            'backend' => [
                'framework' => 'Laravel 10',
                'php_version' => PHP_VERSION,
                'key_directories' => [
                    'app/Http/Controllers' => 'Контроллеры приложения',
                    'app/Models' => 'Eloquent модели',
                    'app/Entity/Actions' => 'Бизнес-логика для сущностей',
                    'app/Helpers' => 'Helper классы (GeoHelper, StorageHelper)',
                    'routes/web.php' => 'Публичные маршруты',
                    'routes/admin.php' => 'Административные маршруты',
                    'database/migrations' => 'Миграции базы данных',
                ],
            ],
            'frontend' => [
                'templating' => 'Blade Templates',
                'css' => 'Tailwind CSS',
                'javascript' => 'Alpine.js',
                'build_tool' => 'Vite',
                'key_directories' => [
                    'resources/views' => 'Blade шаблоны',
                    'resources/css' => 'Стили приложения',
                    'resources/js' => 'JavaScript файлы',
                    'public/build' => 'Скомпилированные assets',
                ],
            ],
            'storage' => [
                'images' => 'Timeweb S3 Cloud Storage (4.64 GB, 20,781 файлов)',
                'local_storage' => 'storage/app/public (для разработки)',
            ],
            'deployment' => [
                'development' => 'Replit (этот сервер)',
                'version_control' => 'GitHub (armx2020/arm-new)',
                'staging' => 'Timeweb Laravel App',
                'production' => 'Timeweb (после переноса домена)',
                'ci_cd' => 'GitHub Webhook → Timeweb Auto-Deploy',
            ],
            'key_features' => [
                'Dynamic Routing' => 'DinamicRouteController обрабатывает разные типы URL',
                'Multi-Database Support' => 'Код работает с MySQL и PostgreSQL',
                'Geo Search' => 'GeoHelper для геопространственных запросов',
                'Fulltext Search' => 'Search trait с поддержкой обеих БД',
                'S3 Integration' => 'Автоматическая загрузка изображений в S3',
            ],
        ];
    }
}
