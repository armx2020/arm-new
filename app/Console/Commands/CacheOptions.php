<?php

namespace App\Console\Commands;

use App\Models\Option;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheOptions extends Command
{
    protected $signature = 'cache-options';
    protected $description = 'Сохранение опций в кэш';

    public function handle()
    {
        Cache::rememberForever('options', function () {

            $options = Option::select('name_ru', 'name_en', 'value')->get();

            if ($options->isEmpty()) {
                Option::create(
                    ['name_ru' => 'активный api-id для смс.ру', 'name_en' => 'api_id_active', 'value' => 'отсутствет']
                );

                Option::create(
                    ['name_ru' => 'тестовый api-id для смс.ру', 'name_en' => 'api_id_deactive', 'value' => 'отсутствет']
                );

                $options = Option::select('name_ru', 'name_en', 'value')->get();
            }

            return $options;
        });

        $this->info('Optons data saved successfully.');
    }
}
