<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TelegramUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $normalized = self::normalize($value);

        preg_match('/\/([a-zA-Z0-9_]+)$/', $value, $matches);
        $username = $matches[1] ?? '';

        if (strlen($username) < 5 || strlen($username) > 32) {
            $fail('Имя пользователя Telegram должно содержать от 5 до 32 символов');
        }

        if (preg_match('/[^a-zA-Z0-9_]/', $username)) {
            $fail('Имя пользователя может содержать только буквы, цифры и подчеркивания');
        }

        if (str_ends_with($username, 'bot') || str_ends_with($username, '_bot')) {
            $fail('Ссылки на ботов не поддерживаются');
        }
        

        if (!preg_match('/^https:\/\/(t\.me|telegram\.me)\/[a-zA-Z0-9_]{5,32}\/?$/i', $normalized)) {
            $fail('Некорректная ссылка Telegram. Допустимые форматы: @username, t.me/username или telegram.me/username');
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
        
        $value = strtok($value, '?');
        
        if (str_starts_with($value, '@')) {
            $value = substr($value, 1);
        }
        
        preg_match('/\/([a-zA-Z0-9_]+)$/', $value, $matches);
        $username = $matches[1] ?? '';
        
        if (!preg_match('/(t\.me|telegram\.me)/i', $value) && preg_match('/^[a-zA-Z0-9_]+$/', $value)) {
            $username = $value;
        }
        
        $username = preg_replace('/[^a-zA-Z0-9_]/', '', $username);
        
        if (empty($username)) {
            return $value;
        }
        
        return 'https://t.me/' . $username;
    }
}
