<?php

namespace App\Http\Traits;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

trait RegionTrait
{
    /**
     * Получает регион с учетом городов и стран
     * Всегда возвращает объект региона
     */
    public function getRegion(Request $request, ?string $regionTranslit = null): object
    {
        $this->ensureRegionsCache();
        $defaultRegion = $this->getDefaultRegion();

        // Если регион не указан - возвращаем дефолтный
        if (empty($regionTranslit)) {
            $this->setSessionData($request, $defaultRegion);
            return $defaultRegion;
        }

        // Ищем локацию (регион/город/страну)
        $location = $this->findLocation($regionTranslit);

        // Если не найдено - возвращаем дефолтный
        if (!$location) {
            $this->setSessionData($request, $defaultRegion);
            return $defaultRegion;
        }

        // Обработка разных типов локаций
        return $this->handleLocationType($request, $location, $defaultRegion);
    }

    protected function handleLocationType(Request $request, object $location, object $defaultRegion): object
    {
        // Для городов возвращаем регион города
        if ($this->isCity($location)) {
            $this->setSessionData($request, $location->region, $location);
            return $location->region;
        }

        // Для стран (кроме России) возвращаем дефолтный регион
        if ($this->isCountry($location) && $location->code !== 'ru') {
            $this->setSessionData($request, $defaultRegion, null, $location);
            return $defaultRegion;
        }

        // Для регионов и России возвращаем сам регион
        $this->setSessionData($request, $location);
        return $location;
    }

    protected function ensureRegionsCache(): void
    {
        if (Cache::missing('all_regions')) {
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
        }
    }

    protected function getDefaultRegion(): object
    {
        return collect(Cache::get('all_regions', []))
            ->firstWhere('id', 1) ?? (object)[
                'id' => 1,
                'name' => 'Россия',
                'transcription' => 'rossiya',
                'lat' => 55.7558,
                'lon' => 37.6176
            ];
    }

    protected function findLocation(string $translit): ?object
    {
        $region = collect(Cache::get('all_regions', []))
            ->firstWhere('transcription', 'like', $translit);
        if ($region) return $region;

        $city = collect(Cache::get('all_cities', []))
            ->firstWhere('transcription', 'like', $translit);
        if ($city) return $city;

        return collect(Cache::get('all_countries', []))
            ->firstWhere('code', 'like', $translit);
    }

    protected function isCity(object $location): bool
    {
        return isset($location->region);
    }

    protected function isCountry(object $location): bool
    {
        return isset($location->code);
    }

    protected function setSessionData(
        Request $request,
        object $region,
        ?object $city = null,
        ?object $country = null
    ): void {
        $isRussia = $region->name === 'Россия' || ($country->code ?? null) === 'ru';

        $request->session()->put([
            'regionName' => $country->name_ru ?? $region->name,
            'regionTranslit' => $country->code ?? $region->transcription,
            'lat' => $city->lat ?? $country->lat ?? $region->lat,
            'lon' => $city->lon ?? $country->lon ?? $region->lon,
            'isRussia' => $isRussia
        ]);
    }
}
