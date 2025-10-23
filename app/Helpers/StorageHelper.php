<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

/**
 * Helper для работы с изображениями
 * Поддерживает загрузку с S3, production сервера или локально
 */
class StorageHelper
{
    public static function imageUrl(?string $path): string
    {
        if (empty($path)) {
            return url('/image/no_photo.jpg');
        }

        // Temporary: Use production images until S3 migration is complete
        // TODO: Switch to S3 after verifying all files are uploaded
        $useProduction = env('USE_PRODUCTION_IMAGES', true);
        
        if ($useProduction) {
            return env('PRODUCTION_STORAGE_URL', 'https://vsearmyane.ru/storage') . '/' . $path;
        }

        $defaultDisk = config('filesystems.default', 'local');
        
        if ($defaultDisk === 's3') {
            $s3Path = 'storage/app/public/' . $path;
            return Storage::disk('s3')->url($s3Path);
        }

        return asset('storage/' . $path);
    }
}
