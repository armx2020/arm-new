<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'viber'     => ['max:36'],
            'whatsapp'  => ['max:36'],
            'telegram'  => ['max:36'],
            'instagram' => ['max:36'],
            'vkontakte' => ['max:36'],
            'image'     => ['image', 'max:20000'],
            'image_remove'   => []
        ];
    }
}
