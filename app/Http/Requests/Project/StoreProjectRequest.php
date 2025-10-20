<?php

namespace App\Http\Requests\Project;

use App\Rules\InstagramUrl;
use App\Rules\TelegramUrl;
use App\Rules\VkontakteUrl;
use App\Rules\WebUrl;
use App\Rules\WhatsappUrl;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255', 'min:3'],
            'address'       => ['nullable', 'string', 'max:128'],
            'phone'         => ['nullable', 'string', 'max:36'],
            'description'   => ['nullable', 'string'],
            'web'           => ['nullable', new WebUrl],
            'whatsapp'      => ['nullable', new WhatsappUrl],
            'telegram'      => ['nullable', new TelegramUrl],
            'instagram'     => ['nullable', new InstagramUrl],
            'vkontakte'     => ['nullable', new VkontakteUrl],
            'city'          => ['integer'],
            'user'          => ['nullable', 'integer'],
            'images'        => ['nullable', 'array', 'max:20'],
            'images.*'      => ['image', 'mimes:jpg,bmp,png', 'max:20480'],
        ];
    }
}
