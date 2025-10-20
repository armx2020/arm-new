<?php

namespace App\Console\Commands;

use App\Services\YandexGeocoderService;
use Illuminate\Console\Command;

class CheckGeocoderLimit extends Command
{
    protected $signature = 'yandex-geocoder:limit';
    protected $description = 'Проверка остатка лимита запросов к Yandex Geocoder API';

    public function handle(YandexGeocoderService $geocoder)
    {
        $used = $geocoder->getUsedRequestsCount();
        $limit = $geocoder->getDailyLimit();
        $remaining = $limit - $used;

        $remainingColor = $remaining < 50 ? 'error' : 'comment';

        $progress = $remaining <= 0 ? 0 : round(100 - ($used / $limit) * 100, 1);

        $this->info("Лимит запросов к Yandex Geocoder:");
        $this->line("Использовано: <comment>{$used}</comment>");
        $this->line("Доступно всего: <comment>{$limit}</comment>");
        $this->line("Осталось сегодня: <{$remainingColor}> {$remaining} ($progress%)</{$remainingColor}> ");

        return 0;
    }
}
