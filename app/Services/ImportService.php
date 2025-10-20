<?php

namespace App\Services;

use App\Models\Entity;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class ImportService
{
    public function setDataFromEntity($oldEntity)
    {
        $oldEntity->chunk(100, function (Collection $oldEntities) {
            foreach ($oldEntities as $entity) {
                $newEntity = Entity::updateOrCreate([
                    'name' => $entity->name,
                    'activity' => $entity->activity,
                    'entity_type_id' => $this->getType($entity->getTable()),
                    'address' => $entity->address,
                    'image' => $this->getNewPathForImage($entity->image),
                    'description' => $entity->description,
                    'phone' => $entity->phone,
                    'web' => $entity->web,
                    'whatsapp' => $entity->whatsapp,
                    'instagram' => $entity->instagram,
                    'vkontakte' => $entity->vkontakte,
                    'telegram' => $entity->telegram,
                    'user_id' => $entity->user_id,
                    'city_id' => $entity->city_id,
                    'region_id' => $entity->region_id,
                    'category_id' => $entity->category_id
                ]);

                if ($entity->getTable() == 'companies') {
                    $this->syncFields($entity, $newEntity);
                    $this->syncOffers($entity, $newEntity);
                }
            }
        });
    }

    public function getType($tableName)
    {
        switch ($tableName) {
            case 'companies':
                return 1;
                break;
            case 'groups':
                return 2;
                break;
            default:
                return null;
                break;
        }
    }

    public function syncFields($fromEntity, $toEntity)
    {
        foreach ($fromEntity->categories as $category) {
            $toEntity->fields()->attach($category->id, ['main_category_id' => $category->pivot->main_category_id]);
        }
    }

    public function getNewPathForImage($imageURL)
    {
        $imageName = null;

        $imageFullUrl = explode("/", $imageURL);

        if (isset($imageFullUrl[1])) {
            $imageName = $imageFullUrl[1];
            Storage::copy('public/' . $imageURL, 'public/uploaded/' . $imageFullUrl[1]);
            $imageName = 'uploaded/' . $imageFullUrl[1];
        }

        return $imageName;
    }

    public function syncOffers($fromEntity, $toEntity)
    {
        foreach ($fromEntity->offers as $offer) {
            $newOffer = Offer::firstOrCreate([
                'name' => $offer->name,
                'address' => $offer->address,
                'image' => $this->getNewPathForImage($offer->image),
                'description' => $offer->description,
                'user_id' => $offer->user_id,
                'city_id' => $offer->city_id,
                'region_id' => $offer->region_id,
                'category_id' => $offer->category_id,
                'entity_id' => $toEntity->id
            ]);
        }
    }
}
