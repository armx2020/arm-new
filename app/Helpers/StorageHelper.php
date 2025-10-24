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

        // Replit webview блокирует внешние HTTPS запросы -> используем production proxy
        if (config('app.env') === 'local' && env('REPL_ID')) {
            return env('PRODUCTION_STORAGE_URL', 'http://vsearmyane.ru/storage') . '/' . $path;
        }

        $defaultDisk = config('filesystems.default', 'local');
        
        if ($defaultDisk === 's3') {
            // Staging/Production используют S3 напрямую
            try {
                return Storage::disk('s3')->url($path);
            } catch (\Exception $e) {
                return asset('storage/' . $path);
            }
        }

        return asset('storage/' . $path);
    }
}
