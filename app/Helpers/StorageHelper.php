<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function imageUrl(?string $path): string
    {
        if (empty($path)) {
            return url('/image/no_photo.jpg');
        }

        if (env('USE_S3_STORAGE', false)) {
            return Storage::disk('s3')->url($path);
        }

        if (app()->environment('local') && env('USE_PRODUCTION_IMAGES', true)) {
            return env('PRODUCTION_STORAGE_URL', 'https://vsearmyne.ru/storage') . '/' . $path;
        }

        return asset('storage/' . $path);
    }
}
