<?php

namespace App\Http\Requests\Profile\Entity;

use App\Rules\InstagramUrl;
use App\Rules\TelegramUrl;
use App\Rules\VkontakteUrl;
use App\Rules\WebUrl;
use App\Rules\WhatsappUrl;
use App\Rules\VideoUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEntityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255', 'min:3'],
            'address'          => ['nullable', 'string', 'max:128'],
            'phone'            => ['nullable', 'string', 'max:36'],
            'description'      => ['nullable', 'string'],
            'director'         => ['nullable', 'string'],
            'video_url'        => ['nullable', new VideoUrl],
            'paymant_link'     => ['nullable', new WebUrl],
            'web'              => ['nullable', new WebUrl],
            'whatsapp'         => ['nullable', new WhatsappUrl],
            'telegram'         => ['nullable', new TelegramUrl],
            'instagram'        => ['nullable', new InstagramUrl],
            'vkontakte'        => ['nullable', new VkontakteUrl],
            'city'             => ['integer'],
            'user'             => ['nullable'],
            'moderator'        => ['nullable'],
            'type'             => ['required', 'integer'],
            'category'         => ['nullable'],
            'fields'           => ['nullable'],
            'activity'         => ['nullable', 'in:1'],
            'sort_id'          => ['required'],
            'images'           => ['nullable', 'array', 'max:20'],
            'images.*.id'      => ['required'],
            'images.*.sort_id' => ['sometimes', 'required', 'integer'],
            'images.*.file'    => ['sometimes', 'nullable', 'file', 'mimes:jpg,jpeg,png', 'max:20480'],
            'images.*.checked' => ['sometimes', 'nullable', 'in:0,1'],
            'logotype'         => ['sometimes', 'nullable', 'image', 'mimes:jpg,bmp,png', 'max:20480'],
            'logotype_remove'     => ['nullable', 'in:delete'],
        ];
    }
}
