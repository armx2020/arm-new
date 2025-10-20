<?php

namespace App\Console\Commands;

use App\Models\Entity;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class AddStandardCommunities extends Command
{
    protected $signature = 'add-standart-communities';
    protected $description = 'Добавление стандартных общин';

    public function handle()
    {
        Region::chunk(100, function (Collection $collections) {
            foreach ($collections as $region) {
                $entity = Entity::updateOrCreate([
                    'name' => 'Армянская община ' . $region->name_dat
                ], 
                [
                    'created_at' => '2023-06-28 14:18:19',
                    'updated_at' => '2023-06-28 14:18:19',
                    'city_id' => 1,
                    'region_id' => $region->id,
                    'activity' => 1,
                    'lat'=> $region->lat,
                    'lon' => $region->lon,
                    'entity_type_id' => 4,
                    'category_id' => 1
                ]);
            }
        });

        $this->info('standart communities added');
    }
}
