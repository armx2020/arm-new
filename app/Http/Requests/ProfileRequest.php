<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\InstagramUrl;
use App\Rules\TelegramUrl;
use App\Rules\VkontakteUrl;
use App\Rules\WhatsappUrl;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'       => ['required', 'string', 'max:32', 'min:2'],
            'whatsapp'        => ['nullable', new WhatsappUrl],
            'telegram'        => ['nullable', new TelegramUrl],
            'instagram'       => ['nullable', new InstagramUrl],
            'vkontakte'       => ['nullable', new VkontakteUrl],
            'image'           => ['nullable', 'image', 'mimes:jpg,bmp,png', 'max:20000'],
            'image_remove'    => ['nullable', 'in:delete'],
        ];
    }
}
