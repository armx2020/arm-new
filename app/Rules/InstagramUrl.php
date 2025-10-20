<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InstagramUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $normalized = self::normalize($value);

        preg_match('/instagram\.com\/([a-zA-Z0-9_.]+)\/?$/i', $normalized, $matches);
        $username = $matches[1] ?? '';

        if (preg_match('/[^a-zA-Z0-9_.]/', $username)) {
            $fail('Имя пользователя содержит недопустимые символы');
        }

        if (strlen($username) > 30) {
            $fail('Имя пользователя Instagram не может превышать 30 символов');
        }

        if (strlen($username) < 3) {
            $fail('Имя пользователя должно содержать минимум 3 символа');
        }
        
        if (!preg_match('/^https:\/\/(www\.)?instagram\.com\/[a-zA-Z0-9_.]{1,30}\/?$/i', $normalized)) {
            $fail('Некорректная ссылка Instagram. Допустимый формат: https://instagram.com/username');
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

        preg_match('/\/([a-zA-Z0-9_.]+)\/?$/', $value, $matches);
        $username = $matches[1] ?? '';

        if (!preg_match('/instagram\.com/i', $value) && preg_match('/^[a-zA-Z0-9_.]+$/', $value)) {
            $username = $value;
        }

        $username = preg_replace('/[^a-zA-Z0-9_.]/', '', $username);
        
        if (empty($username)) {
            return $value;
        }
        
        $normalized = 'https://instagram.com/' . $username;
        
        return rtrim($normalized, '/');
    }
}
