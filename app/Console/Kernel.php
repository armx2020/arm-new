<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('parse-telegram')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        $schedule->command('app:calculate-doubles')->dailyAt('00:00');
        $schedule->command('app:calculate-fullness')->dailyAt('00:00');
        $schedule->command('app:calculate-top-entities')->dailyAt('00:00');
        $schedule->command('routes:update')->dailyAt('02:00');
        $schedule->command('cache-regions')->dailyAt('02:10');
        $schedule->command('cache-options')->dailyAt('02:20');
        $schedule->command('app:create-site-map --create --truncate')->dailyAt('03:00');

        $schedule->call(function () {
            Cache::forget('yandex_geocoder_used_requests');
        })->dailyAt('00:00'); // Ежедневный сброс счётчика лимита Яндекс HTTP API

        // Обработка очереди каждую минуту
        $schedule->command('queue:work --stop-when-empty --max-time=60')->dailyAt('01:00');

        // Очистка завершенных задач 
        $schedule->command('queue:prune-failed')->daily();
        $schedule->command('queue:prune-batches')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
