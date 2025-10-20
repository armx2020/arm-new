<?php

namespace App\Entity\Actions;

use App\Models\Project;
use App\Entity\Actions\Traits\GetCity;
use App\Models\Entity;
use App\Models\Region;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class ProjectAction
{
    use GetCity;

    public function store($request, $user_id = null, $isActive = true)
    {
        $city = $this->getCity($request);

        $entity = new Entity();

        if ($isActive == false) {
            $entity->activity = $isActive;
        }

        $entity->entity_type_id = 12;
        $entity->name = $request->name;
        $entity->address = $request->address;
        $entity->description = $request->description;
        $entity->city_id = $city->id;
        $entity->region_id = $city->region->id;
        $entity->phone = $request->phone;
        $entity->web = $request->web;
        $entity->whatsapp = $request->whatsapp;
        $entity->telegram = $request->telegram;
        $entity->instagram = $request->instagram;
        $entity->vkontakte = $request->vkontakte;
        $entity->user_id = $user_id ?: $request->user;
        $entity->category_id = $request->category;

        // Обработка координат
        if ($request->has('latitude') && $request->has('longitude')) {
            $entity->lat = $request->latitude;
            $entity->lon = $request->longitude;
        }

        // Определение геолокации
        $city = $this->resolveCity($request);
        $entity->city_id = $city->id;

        if ($city->id == 1) {
            $region = $this->resolveRegion($request);
            $entity->region_id = $region->id;
        } else {
            $entity->region_id = $city->region_id;
        }

        $entity->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $sortId => $file) {
                $path = $file->store('uploaded', 'public');

                $imageEntity = $entity->images()->create([
                    'path' => $path,
                    'sort_id' => $sortId,
                ]);

                Image::make('storage/' . $imageEntity->path)
                    ->resize(400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save();
            }
        }

        return $entity;
    }

    protected function resolveCity($request)
    {
        if ($request->has('city') && $request->city) {
            $city = City::where('name', 'like', '%' . $request->city . '%')->first();
            if ($city) {
                return $city;
            }
        }

        // Пытаемся определить город по координатам
        if ($request->has('latitude') && $request->has('longitude')) {
            $city = $this->findCityByCoordinates(
                $request->latitude,
                $request->longitude
            );
            if ($city) {
                return $city;
            }
        }

        // Город по умолчанию (id = 1)
        return City::find(1);
    }

    protected function resolveRegion($request)
    {
        if ($request->has('region') && $request->region) {
            $region = Region::where('name', 'like', '%' . $request->region . '%')->first();
            if ($region) {
                return $region;
            }
        }

        // Пытаемся определить регион по координатам
        if ($request->has('latitude') && $request->has('longitude')) {
            $region = $this->findRegionByCoordinates(
                $request->latitude,
                $request->longitude
            );
            if ($region) {
                return $region;
            }
        }

        // Регион по умолчанию (id = 1)
        return Region::find(1);
    }

    protected function findCityByCoordinates($lat, $lon)
    {
        // Простейшая реализация - ищем ближайший город в радиусе 50 км
        return City::selectRaw(
            '*, ST_Distance_Sphere(
                POINT(?, ?),
                POINT(lon, lat)
            ) as distance',
            [$lon, $lat]
        )
            ->whereRaw('ST_Distance_Sphere(
            POINT(lon, lat),
            POINT(?, ?)
        ) < ?', [$lon, $lat, 50000]) // 50 км в метрах
            ->orderBy('distance')
            ->first();
    }

    protected function findRegionByCoordinates($lat, $lon)
    {
        return Region::selectRaw(
            '*, ST_Distance_Sphere(
                POINT(?, ?),
                POINT(lon, lat)
            ) as distance',
            [$lon, $lat]
        )
        ->whereRaw('ST_Distance_Sphere(
            POINT(lon, lat),
            POINT(?, ?)
        ) < ?', [$lon, $lat, 10000]) // 100 км в метрах
        ->orderBy('distance')
        ->first();
    }
}
