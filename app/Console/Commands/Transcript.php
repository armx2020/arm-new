<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\City;
use App\Models\Entity;
use App\Models\Region;
use App\Services\TranscriptService;
use Illuminate\Console\Command;

class Transcript extends Command
{
    protected $signature = 'app:transcript';

    protected $description = 'транскрипция таблиц';

    public function handle(TranscriptService $service)
    {
        $service->translitName(Category::query());
        // $service->translitName(EntityType::query());
        // $service->translitName(Entity::query());
        // $service->translitName(City::query());
        // $service->translitName(Region::query());
    }
}
