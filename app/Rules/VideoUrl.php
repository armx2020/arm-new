<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VideoUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (!$this->isValidVideoUrl($value)) {
            $fail('Поле :attribute должно содержать валидную ссылку YouTube или RuTube');
        }
    }

    protected function isValidVideoUrl(string $value): bool
    {
        // Проверка YouTube
        if (preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/)|youtu\.be\/)[^&]+/i', $value)) {
            return true;
        }

        // Проверка RuTube
        if (preg_match('/^(https?:\/\/)?(www\.)?rutube\.ru\/(video|embed)\/[a-f0-9]{32}/i', $value)) {
            return true;
        }

        // Проверка iframe
        if (preg_match('/<iframe.*src=".*(youtube\.com\/embed\/|rutube\.ru\/embed\/)[^"]+".*<\/iframe>/i', $value)) {
            return true;
        }

        return false;
    }

    public static function normalize(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Сначала очищаем от HTML-атрибутов
        $value = preg_replace('/\s*(frameborder|allowfullscreen|allow|scrolling|style|class)\s*=\s*["\'][^"\']*["\']/i', '', $value);
        $value = preg_replace('/\s*\/?\s*>/', '', $value);

        // YouTube обработка
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/i', $value, $matches)) {
            return "https://youtube.com/{$matches[1]}";
        }

        if (preg_match('/youtu\.be\/([^\/?]+)/i', $value, $matches)) {
            return "https://youtube.com/{$matches[1]}";
        }

        if (preg_match('/youtube\.com\/([^\/?]+)/i', $value, $matches) && !str_contains($matches[1], 'embed')) {
            return "https://youtube.com/{$matches[1]}";
        }

        if (preg_match('/youtube\.com\/embed\/([^\/?]+)/i', $value, $matches)) {
            return "https://youtube.com/{$matches[1]}";
        }

        if (preg_match('/<iframe.*src=".*youtube[^\/]+\/(?:embed\/|watch\?v=)?([^\/?"&]+).*<\/iframe>/i', $value, $matches)) {
            return "https://youtube.com/{$matches[1]}";
        }

        // RuTube обработка
        if (preg_match('/rutube\.ru\/video\/([a-f0-9]{32})/i', $value, $matches)) {
            return "https://rutube.ru/video/{$matches[1]}/";
        }

        if (preg_match('/rutube\.ru\/embed\/([a-f0-9]{32})/i', $value, $matches)) {
            return "https://rutube.ru/video/{$matches[1]}/";
        }

        if (preg_match('/<iframe.*src=".*rutube\.ru\/embed\/([a-f0-9]{32}).*<\/iframe>/i', $value, $matches)) {
            return "https://rutube.ru/video/{$matches[1]}/";
        }

        return trim($value);
    }
}
