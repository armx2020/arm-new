<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    /**
     * Конвертирует изображение в WebP формат
     * @param string $sourcePath - путь к исходному изображению
     * @param int $quality - качество WebP (0-100, по умолчанию 80)
     * @return string|false - путь к WebP изображению или false при ошибке
     */
    public static function convertToWebP(string $sourcePath, int $quality = 80)
    {
        try {
            $image = Image::make($sourcePath);
            
            // Заменяем расширение на .webp
            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $sourcePath);
            
            // Сохраняем в WebP формате
            $image->encode('webp', $quality)->save($webpPath);
            
            return $webpPath;
        } catch (\Exception $e) {
            \Log::error('WebP conversion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Оптимизирует и сохраняет изображение с опциональной конвертацией в WebP
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory - директория для сохранения
     * @param int $maxWidth - максимальная ширина (по умолчанию 1200)
     * @param bool $createWebP - создавать ли WebP версию
     * @param string $disk - диск для сохранения
     * @return array ['original' => string, 'webp' => string|null]
     */
    public static function optimizeAndStore($file, string $directory, int $maxWidth = 1200, bool $createWebP = true, string $disk = 'public'): array
    {
        // Сохраняем оригинал
        $path = $file->store($directory, $disk);
        $fullPath = storage_path('app/' . $disk . '/' . $path);
        
        // Оптимизируем размер
        $image = Image::make($fullPath);
        if ($image->width() > $maxWidth) {
            $image->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($fullPath, 85); // Качество 85% для баланса размер/качество
        }

        $result = ['original' => $path, 'webp' => null];

        // Создаём WebP версию если нужно
        if ($createWebP) {
            $webpPath = self::convertToWebP($fullPath);
            if ($webpPath) {
                // Получаем относительный путь
                $result['webp'] = str_replace(storage_path('app/' . $disk . '/'), '', $webpPath);
            }
        }

        return $result;
    }
}
