<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class GeoHelper
{
    public static function isGeoSearchSupported(): bool
    {
        return config('database.default') === 'mysql';
    }

    public static function nearestCityQuery($model, $lat, $lon, $radius = 50000)
    {
        if (!self::isGeoSearchSupported()) {
            return null;
        }

        return $model::selectRaw(
            '*, ST_Distance_Sphere(
                POINT(lon, lat),
                POINT(?, ?)
            ) AS distance',
            [$lon, $lat]
        )
        ->whereRaw('ST_Distance_Sphere(
            POINT(lon, lat),
            POINT(?, ?)
        ) <= ?', [$lon, $lat, $radius])
        ->orderBy('distance')
        ->first();
    }

    public static function nearestRegionQuery($model, $lat, $lon, $radius = 200000)
    {
        if (!self::isGeoSearchSupported()) {
            return null;
        }

        return $model::selectRaw(
            '*, ST_Distance_Sphere(
                POINT(lon, lat),
                POINT(?, ?)
            ) AS distance',
            [$lon, $lat]
        )
        ->whereRaw('ST_Distance_Sphere(
            POINT(lon, lat),
            POINT(?, ?)
        ) <= ?', [$lon, $lat, $radius])
        ->orderBy('distance')
        ->first();
    }

    public static function nearbyEntities(Builder $query, $lat, $lon, $radius = 10000)
    {
        if (!self::isGeoSearchSupported()) {
            return $query;
        }

        return $query->selectRaw(
            "id, name, lat, lon, ST_Distance_Sphere(POINT(lon, lat), POINT(?, ?)) AS distance",
            [$lon, $lat]
        )
        ->having('distance', '<=', $radius)
        ->orderBy('distance');
    }
}
