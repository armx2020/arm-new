<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VkontakteUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $normalized = self::normalize($value);

        preg_match('/instagram\.com\/([a-zA-Z0-9_.]+)\/?$/i', $normalized, $matches);
        $username = $matches[1] ?? '';

        if (strlen($username) < 5) {
            $fail('Имя пользователя VK должно содержать минимум 5 символов');
        }

        if (!preg_match('/^https:\/\/vk\.com\/id\d+$/', $normalized)) {
            $fail('Допускаются только ссылки с числовым ID (например: vk.com/id12345)');
        }

        if (preg_match('/[^a-zA-Z0-9_.-]/', $username)) {
            $fail('Имя пользователя содержит недопустимые символы');
        }

        if (!preg_match('/^https:\/\/(vk\.com|vkontakte\.ru)\/[a-zA-Z0-9_.-]+$/i', $normalized)) {
            $fail('Некорректная ссылка VK. Допустимые форматы: vk.com/username или vkontakte.ru/username');
        }

        if (!preg_match('/^https:\/\/vk\.com\/id\d+$/', $normalized)) {
            $fail('Допускаются только ссылки с числовым ID (например: vk.com/id12345)');
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

        preg_match('/\/([a-zA-Z0-9_.-]+)$/', $value, $matches);
        $username = $matches[1] ?? '';

        $value = preg_replace('/^(.*?)(vk\.com|vkontakte\.ru)/i', '', $value);
        $value = preg_replace('/[^a-zA-Z0-9_.-]/', '', $value);

        if (empty($username) && empty($value)) {
            return $value;
        }

        $normalized = 'https://vk.com/' . ($username ?: $value);

        return rtrim($normalized, '/');
    }
}
