<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Entity;
use App\Models\Image;

class CalculateFullness extends Command
{
    protected $signature = 'app:calculate-fullness';

    protected $description = 'Calculate fullness for all entities';

    public function handle()
    {
        $this->info('Starting fullness calculation...');

        Entity::chunk(500, function ($entities) {
            foreach ($entities as $entity) {
                $fullness = 0;
                $hasMainImage = Image::where('imageable_id', $entity->id)
                    ->where('sort_id', 0)
                    ->exists();
                if ($hasMainImage) $fullness += 30;
                $otherImagesCount = Image::where('imageable_id', $entity->id)
                    ->whereBetween('sort_id', [1, 5])
                    ->count();
                if ($otherImagesCount > 0) $fullness += 25;
                if (!empty($entity->phone)) $fullness += 25;
                if (!empty($entity->description)) {
                    $fullness += 10;
                }
                if (!empty($entity->web) || !empty($entity->instagram) || !empty($entity->vkontakte)) {
                    $fullness += 10;
                }
                Entity::where('id', $entity->id)->update(['fullness' => $fullness]);
            }
        });

        $this->info('Fullness calculation completed.');
    }
}
