<?php

namespace App\Console\Commands;

use App\Models\EntityType;
use Doctrine\Inflector\InflectorFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateDynamicRoutes extends Command
{
    protected $signature = 'routes:update';
    protected $description = 'Обновление динамических роутов (типы сущностей) и сохранение их в кэш';

    public function handle()
    {
        $inflector = InflectorFactory::create()->build();

        $entityTypes = EntityType::active()->pluck('transcription')->toArray();

        $items = [];

        foreach ($entityTypes as $plural) {
            $singular = $inflector->singularize($plural);

            if ($plural !== $singular) {
                $items[$plural] = $inflector->singularize($plural);
            }
        }

        $collection = collect($items)->toArray();

        // Сохраняем данные в кэше
        Cache::put('dynamic_routes', $collection);
        dump($collection);
        $this->info('Dynamic routes data updated successfully.');
    }
}
