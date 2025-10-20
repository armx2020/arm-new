<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WebUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $normalized = self::normalize($value);
        
        if (!filter_var($normalized, FILTER_VALIDATE_URL)) {
            $fail('Некорректный URL сайта. Допустимый формат: https://example.com');
        }
        
        if (!preg_match('/^https:\/\/[a-z0-9\-]+(\.[a-z0-9\-]+)+/i', $normalized)) {
            $fail('URL должен содержать доменное имя (например: example.com)');
        }

        if (preg_match('/https:\/\/[^\/]+\.[^\/]+\.[^\/]+/i', $normalized)) {
            $fail('Используйте основной домен без поддоменов');
        }
    }

    public static function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }
        
        $value = preg_replace('/^(.*?)([a-z0-9\-]+\.[a-z0-9\-]+)/i', '$2', $value);

        $value = ltrim($value, '/');
        
        if (!preg_match('/^(http:\/\/|https:\/\/)/i', $value)) {
            $value = 'https://' . $value;
        }
        
        $value = preg_replace('/^http:\/\//i', 'https://', $value);
        
        return rtrim($value, '/');
    }
}
