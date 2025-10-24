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

        // Для Replit используем production proxy т.к. Timeweb блокирует прямые S3 запросы
        if (config('app.env') === 'local' && env('REPL_ID')) {
            return env('PRODUCTION_STORAGE_URL', 'https://vsearmyane.ru/storage') . '/' . $path;
        }

        $defaultDisk = config('filesystems.default', 'local');
        
        if ($defaultDisk === 's3') {
            // Для staging/production используем S3 напрямую
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
