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

        // Сбор данных о проекте
        $projectInfo = $this->getProjectInfo();
        $databaseStatus = $this->getDatabaseStatus();
        $s3Status = $this->getS3Status();
        $systemStatus = $this->getSystemStatus();
        $statistics = $this->getStatistics();
        $environmentInfo = $this->getEnvironmentInfo();

        return view('admin.diagnostics', [
            'projectInfo' => $projectInfo,
            'databaseStatus' => $databaseStatus,
            's3Status' => $s3Status,
            'systemStatus' => $systemStatus,
            'statistics' => $statistics,
            'environmentInfo' => $environmentInfo,
        ]);
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
}
