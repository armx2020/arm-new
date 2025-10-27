<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportDemoData extends Command
{
    protected $signature = 'demo:export';
    protected $description = 'Export 10 records from each table for DEMO mode';

    public function handle()
    {
        $this->info('🚀 Экспортирую данные из MySQL для DEMO режима...');
        $this->newLine();

        $tables = [
            'entities',
            'regions',
            'categories',
            'users',
            'telegram_groups',
            'telegram_messages',
            'offers',
            'images',
        ];

        $path = storage_path('demo-data');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        foreach ($tables as $table) {
            try {
                $this->info("📦 Экспорт {$table}...");
                
                $data = DB::connection('mysql_dev')
                    ->table($table)
                    ->limit(10)
                    ->get();
                
                $filename = "{$path}/{$table}.json";
                file_put_contents(
                    $filename,
                    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                );
                
                $size = round(filesize($filename) / 1024, 2);
                $this->line("   ✅ {$data->count()} записей, {$size} КБ");
                
            } catch (\Exception $e) {
                $this->error("   ❌ Ошибка: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('✅ Экспорт завершён! Файлы в storage/demo-data/');
        
        return 0;
    }
}
