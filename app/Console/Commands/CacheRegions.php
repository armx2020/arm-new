<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheRegions extends Command
{
    protected $signature = 'cache-regions';
    protected $description = 'Сохранение стран, регионов и городав в кэш';

    public function handle()
    {
        // Для меню
        Cache::rememberForever('regions', function () {
            return Region::whereNot('id', 1)->get()->sortBy('name')
                ->groupBy(function ($item) {
                    return mb_substr($item->name, 0, 1);
                });
        });

        Cache::rememberForever('countries', function () {
            return Country::whereNot('id', 190)->get()->sortBy('name_ru')
                ->groupBy(function ($item) {
                    return mb_substr($item->name_ru, 0, 1);
                });
        });

        Cache::rememberForever('all_regions', function () {
            return Region::all();
        });

        Cache::rememberForever('all_cities', function () {
            return City::all();
        });
        Cache::rememberForever('all_countries', function () {
            return Country::all();
        });

        $this->info('Countries, regions and cities data saved successfully.');
    }
}
