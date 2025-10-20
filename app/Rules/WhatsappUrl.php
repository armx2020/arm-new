<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WhatsappUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $isValid = preg_match(
            '/^(https?:\/\/)?(www\.)?(wa\.me\/[^\s]+|api\.whatsapp\.com\/send\/\?phone=[^\s]+)$/i', 
            $value
        );

        if (!$isValid) {
            $fail('Некорректная ссылка WhatsApp. Допустимые форматы: https://wa.me/номер или https://api.whatsapp.com/send/?phone=номер');
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
        
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return 'https://' . ltrim($value, '/');
    }
}
