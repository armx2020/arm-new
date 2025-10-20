<?php

namespace App\Http\Requests\Admin\User;

use App\Rules\InstagramUrl;
use App\Rules\TelegramUrl;
use App\Rules\VkontakteUrl;
use App\Rules\WhatsappUrl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'    => ['required', 'string', 'max:32', 'min:3'],
            'email'        => ['nullable', 'string', 'email', 'max:255', 'unique:App\Models\User,email'],
            'phone'        => ['required', 'string', 'max:36'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'whatsapp'      => ['nullable', new WhatsappUrl],
            'telegram'      => ['nullable', new TelegramUrl],
            'instagram'     => ['nullable', new InstagramUrl],
            'vkontakte'     => ['nullable', new VkontakteUrl],
            'city'          => ['integer'],
            'image'         => ['image', 'max:20000'],
            'image_remove'      => []
        ];
    }
}
