<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CheckCronWork extends Command
{
    protected $signature = 'check:cron';
    protected $description = 'Проверка работы Cron';

    public function handle()
    {
        $logMessage = 'Cron работает! Время выполнения: ' . now()->toDateTimeString();
        
        // Логируем в файл
        Log::channel('cron')->info($logMessage);
        
        // Пишем в специальный файл для проверки
        Storage::append('cron_check.log', $logMessage);
        
        // Выводим в консоль
        $this->info($logMessage);
        
        return 0;
    }
}
