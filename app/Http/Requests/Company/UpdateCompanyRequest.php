<?php

namespace App\Http\Requests\Company;

use App\Rules\InstagramUrl;
use App\Rules\TelegramUrl;
use App\Rules\VkontakteUrl;
use App\Rules\WebUrl;
use App\Rules\WhatsappUrl;
use App\Rules\VideoUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255', 'min:3'],
            'city'          => ['nullable', 'string'],
            'region'        => ['nullable', 'string'],
            'address'       => ['nullable', 'string', 'max:255'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],
            'phone'            => ['nullable', 'string', 'max:36'],
            'description'      => ['nullable', 'string'],
            'web'              => ['nullable', new WebUrl],
            'video_url'        => ['nullable', new VideoUrl],
            'whatsapp'         => ['nullable', new WhatsappUrl],
            'telegram'         => ['nullable', new TelegramUrl],
            'instagram'        => ['nullable', new InstagramUrl],
            'vkontakte'        => ['nullable', new VkontakteUrl],
            'user'             => ['nullable', 'integer'],
            'fields'           => ['required'],
            'images'           => ['nullable', 'array', 'max:20'],
            'images.*.id'      => ['sometimes', 'required'],
            'images.*.sort_id' => ['sometimes', 'required', 'integer'],
            'images.*.file'    => ['sometimes', 'nullable', 'file', 'mimes:jpg,jpeg,png', 'max:20480'],
            'logotype'         => ['sometimes', 'nullable', 'image', 'mimes:jpg,bmp,png', 'max:20480'],
            'logotype_remove'     => ['nullable', 'in:delete'],
        ];
    }
}
