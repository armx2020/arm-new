<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'address'       => ['max:128'],
            'phone'         => ['max:36'],
            'web'           => ['max:250'],
            'viber'         => ['max:36'],
            'whatsapp'      => ['max:36'],
            'telegram'      => ['max:36'],
            'instagram'     => ['max:36'],
            'vkontakte'     => ['max:36'],
            'image'         => ['image', 'max:20000'],
            'image1'        => ['image', 'max:20000'],
            'image2'        => ['image', 'max:20000'],
            'image3'        => ['image', 'max:20000'],
            'image4'        => ['image', 'max:20000'],
            'image_remove'       => [],
            'image_remove1'      => [],
            'image_remove2'      => [],
            'image_remove3'      => [],
            'image_remove4'      => [],
            'description'   => [],
            'city'          => [],
            'category'      => [],
            'company'       => []
        ];
    }
}
