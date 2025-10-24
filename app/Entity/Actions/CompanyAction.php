<?php

namespace App\Entity\Actions;

use App\Entity\Actions\Traits\GetCity;
use App\Models\Category;
use App\Models\City;
use App\Models\Entity;
use App\Models\Region;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class CompanyAction
{
    use GetCity;

    public function store($request, $user_id = null, $isActive = true): Entity
    {
        $city = $this->getCity($request);

        $entity = new Entity();

        if ($isActive == false) {
            $entity->activity = $isActive;
        }

        $entity->entity_type_id = 1;
        $entity->name = $request->name;
        $entity->address = $request->address;
        $entity->description = $request->description;
        $entity->director = $request->director;
        $entity->city_id = $city->id;
        $entity->region_id = $city->region->id;
        $entity->phone = $request->phone;
        $entity->web = $request->web;
        $entity->video_url = $request->video_url;
        $entity->whatsapp = $request->whatsapp;
        $entity->telegram = $request->telegram;
        $entity->instagram = $request->instagram;
        $entity->vkontakte = $request->vkontakte;
        $entity->user_id = $user_id ?: $request->user;

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

        // Images
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

        // logo
        if ($request->hasFile('logotype')) {

            $path = $request->file('logotype')->store('uploaded', 'public');
            $imageEntity = $entity->images()->create([
                'path' => $path,
                'is_logo' => 1
            ]);
            Image::make('storage/' . $imageEntity->path)
                ->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save();
        }

        // fields
        if ($request->fields) {
            foreach ($request->fields as $categoryID) {
                $categoryBD = Category::find($categoryID);

                if ($categoryBD) {
                    $categoryMain = $categoryBD->category_id;

                    if ($entity->category_id == null) {
                        $entity->category_id = $categoryMain;
                        $entity->save();
                    }
                    $entity->fields()->attach($categoryID, ['main_category_id' => $categoryMain]);
                }
            }
        }

        return $entity;
    }

    public function update($request, $entity, $user_id = null): Entity
    {
        $city = $this->getCity($request);

        $entity->name = $request->name;
        $entity->address = $request->address;
        $entity->description = $request->description;
        $entity->director = $request->director;
        $entity->city_id = $city->id;
        $entity->region_id = $city->region->id;
        $entity->phone = $request->phone;
        $entity->web = $request->web;
        $entity->video_url = $request->video_url;
        $entity->whatsapp = $request->whatsapp;
        $entity->telegram = $request->telegram;
        $entity->instagram = $request->instagram;
        $entity->vkontakte = $request->vkontakte;
        $entity->user_id = $user_id ?: $request->user;

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

        $entity->fields()->detach();

        $entity->update();

        if ($request->fields) {
            foreach ($request->fields as $categoryID) {
                $categoryBD = Category::find($categoryID);

                if ($categoryBD) {
                    $categoryMain = $categoryBD->category_id;

                    if ($entity->category_id == null) {
                        $entity->category_id = $categoryMain;
                        $entity->save();
                    }
                    $entity->fields()->attach($categoryID, ['main_category_id' => $categoryMain]);
                }
            }
        }

        $oldImages = $entity->images;
        $imagesData = $request->input('images', []);
        $oldIDs = $oldImages->pluck('id');

        $incomingIDs = collect($imagesData)
            ->filter(fn($item) => !str_starts_with($item['id'], 'new_'))
            ->pluck('id');

        $idsToDelete = $oldIDs->diff($incomingIDs);

        if ($idsToDelete->isNotEmpty()) {
            $images = $entity->images()->whereIn('id', $idsToDelete)->get();
            foreach ($images as $image) {
                if ($image->path) {
                    Storage::delete('public/' . $image->path);
                }
            }
            $entity->images()->whereIn('id', $idsToDelete)->delete();
        }

        $oldImagesMap = $oldImages->keyBy('id');

        foreach ($imagesData as $index => $imgData) {
            $sortId  = $imgData['sort_id'] ?? $index;
            $imageId = $imgData['id'];

            if (str_starts_with($imageId, 'new_')) {
                $file = $request->file("images.$index.file");
                if ($file) {
                    $path = $file->store('uploaded', 'public');

                    $newImage = $entity->images()->create([
                        'sort_id' => $sortId,
                        'path'    => $path,
                    ]);

                    Image::make('storage/' . $newImage->path)
                        ->resize(400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save();
                }
            } else {
                $oldImage = $oldImagesMap->get($imageId);
                if ($oldImage) {
                    $oldImage->sort_id = $sortId;
                    $oldImage->save();
                }
            }
        }

        // logo
        if ($request->logotype_remove == 'delete') {
            $entity->deleteLogo();
        }

        if ($request->hasFile('logotype')) {
            $entity->deleteLogo();
            $path = $request->file('logotype')->store('uploaded', 'public');
            $imageEntity = $entity->images()->create([
                'path' => $path,
                'is_logo' => 1
            ]);
            Image::make('storage/' . $imageEntity->path)
                ->resize(400, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save();
        }

        return $entity;
    }

    public function destroy($entity): void
    {
        foreach ($entity->images as $image) {
            Storage::delete('public/' . $image->path);
            $image->delete();
        }

        $entity->deleteLogo();
        $entity->fields()->detach();
        $entity->offers()->delete();
        $entity->delete();
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
        return \App\Helpers\GeoHelper::nearestCityQuery(City::class, $lat, $lon, 50000);
    }

    protected function findRegionByCoordinates($lat, $lon)
    {
        return \App\Helpers\GeoHelper::nearestRegionQuery(Region::class, $lat, $lon, 200000);
    }
}
