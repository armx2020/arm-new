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

        // Только для Replit development используем production images
        // т.к. S3 bucket не настроен для публичного доступа
        if (config('app.env') === 'local' && env('REPL_ID')) {
            return env('PRODUCTION_STORAGE_URL', 'https://vsearmyane.ru/storage') . '/' . $path;
        }

        $defaultDisk = config('filesystems.default', 'local');
        
        if ($defaultDisk === 's3') {
            // Используем S3 URL (работает если bucket публичный или через staging nginx proxy)
            try {
                return Storage::disk('s3')->url($path);
            } catch (\Exception $e) {
                // Fallback на локальное хранилище
                return asset('storage/' . $path);
            }
        }

        return asset('storage/' . $path);
    }
}
