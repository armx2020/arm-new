<?php

namespace App\Entity\Actions;

use App\Models\Appeal;
use App\Helpers\ImageUploadHelper;
use Illuminate\Support\Facades\Storage;

class AppealAction
{
    public function store($request, $entityId = null, $userId = null): Appeal
    {
        $appeal = new Appeal();

        $appeal->name = $request->name ?: 'не указано';
        $appeal->phone = $request->phone?: 'не указано';
        $appeal->message = $request->message;
        $appeal->entity_id = $entityId;
        $appeal->user_id = $userId;

        $appeal->save();

        // Загрузка изображений (оптимизированная версия)
        foreach (['image_1', 'image_2', 'image_3', 'image_4', 'image_5'] as $imageField) {
            if ($request->hasFile($imageField)) {
                $path = ImageUploadHelper::processAndStore(
                    $request->file($imageField),
                    'uploaded',
                    400,
                    config('filesystems.default')
                );
                $appeal->images()->create(['path' => $path]);
            }
        }

        return $appeal;
    }

    public function update($request, $appeal): Appeal
    {
        $appeal->message = $request->message;
        $appeal->activity = $request->activity ? 1 : 0;

        // Получаем существующие изображения один раз (вместо многократных запросов)
        $existingImages = $appeal->images()->get();

        // Удаление старых изображений (оптимизированная версия)
        foreach (['image_remove_1', 'image_remove_2', 'image_remove_3', 'image_remove_4', 'image_remove_5'] as $index => $removeField) {
            $imageField = 'image_' . ($index + 1);
            
            if ($request->$removeField == 'delete' || $request->hasFile($imageField)) {
                if (isset($existingImages[$index])) {
                    // Используем правильный диск (работает с S3)
                    Storage::disk(config('filesystems.default'))->delete($existingImages[$index]->path);
                    $existingImages[$index]->delete();
                }
            }
        }

        // Загрузка новых изображений (оптимизированная версия)
        foreach (['image_1', 'image_2', 'image_3', 'image_4', 'image_5'] as $imageField) {
            if ($request->hasFile($imageField)) {
                $path = ImageUploadHelper::processAndStore(
                    $request->file($imageField),
                    'uploaded',
                    400,
                    config('filesystems.default')
                );
                $appeal->images()->create(['path' => $path]);
            }
        }

        $appeal->update();

        return $appeal;
    }

    public function storePhotoToEntity($request, $entity)
    {
        // Загрузка изображений к сущности (оптимизированная версия)
        foreach (['image_1', 'image_2', 'image_3', 'image_4', 'image_5'] as $imageField) {
            if ($request->hasFile($imageField)) {
                $path = ImageUploadHelper::processAndStore(
                    $request->file($imageField),
                    'uploaded',
                    400,
                    config('filesystems.default')
                );
                $entity->images()->create([
                    'path' => $path,
                    'checked' => false,
                ]);
            }
        }

        return $entity;
    }
}
