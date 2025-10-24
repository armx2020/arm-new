<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

/**
 * Helper для работы с изображениями
 * Поддерживает загрузку с S3, production сервера или локально
 */
class StorageHelper
{
    /**
     * Генерирует URL для изображения
     * S3: использует root path из config, не добавляет префикс
     */
    public static function imageUrl(?string $path): string
    {
        if (empty($path)) {
            return url('/image/no_photo.jpg');
        }

        $defaultDisk = config('filesystems.default', 'local');
        
        if ($defaultDisk === 's3') {
            // Все окружения используют S3 напрямую
            try {
                return Storage::disk('s3')->url($path);
            } catch (\Exception $e) {
                return asset('storage/' . $path);
            }
        }

        return asset('storage/' . $path);
    }
}
