<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;

class CalculateTopEntities extends Command
{
    protected $signature = 'app:calculate-top-entities';

    protected $description = 'Calculate top entities based on fullness';

    public function handle()
    {
        $this->info('Calculating top entities...');

        DB::transaction(function () {
            Entity::query()->update(['region_top' => 0, 'city_top' => 0]);

            $allEntities = Entity::select('id', 'region_id', 'city_id', 'entity_type_id', 'fullness')
                ->orderByDesc('fullness')
                ->get();

            $groupedByRegion = $allEntities->groupBy(function ($item) {
                return $item->region_id . '-' . $item->entity_type_id;
            });

            $regionUpdates = [];
            foreach ($groupedByRegion as $group) {
                foreach ($group->take(3) as $index => $entity) {
                    $regionUpdates[$entity->id] = $index + 1;
                }
            }
            $groupedByCity = $allEntities->groupBy(function ($item) {
                return $item->city_id . '-' . $item->entity_type_id;
            });

            $cityUpdates = [];
            foreach ($groupedByCity as $group) {
                foreach ($group->take(3) as $index => $entity) {
                    $cityUpdates[$entity->id] = $index + 1;
                }
            }
            foreach ($regionUpdates as $id => $rank) {
                Entity::where('id', $id)->update(['region_top' => $rank]);
            }
            foreach ($cityUpdates as $id => $rank) {
                Entity::where('id', $id)->update(['city_top' => $rank]);
            }
        });

        $this->info('Top entity calculation completed.');
    }
}
