<?php

namespace App\Http\Requests\Appeal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppealRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'           => ['nullable', 'string', 'max:32'],
            'phone'          => ['nullable', 'string', 'max:36'],
            'message'        => ['string'],
            'image_1'         => ['nullable', 'image', 'mimes:jpg,bmp,png', 'max:20000'],
            'image_remove_1'  => ['nullable', 'in:delete'],
            'image_2'         => ['nullable', 'image', 'mimes:jpg,bmp,png', 'max:20000'],
            'image_remove_2'  => ['nullable', 'in:delete'],
            'image_3'         => ['nullable', 'image', 'mimes:jpg,bmp,png', 'max:20000'],
            'image_remove_3'  => ['nullable', 'in:delete'],
            'image_4'         => ['nullable', 'image', 'mimes:jpg,bmp,png', 'max:20000'],
            'image_remove_4'  => ['nullable', 'in:delete'],
            'image_5'         => ['nullable', 'image', 'mimes:jpg,bmp,png', 'max:20000'],
            'image_remove_5'  => ['nullable', 'in:delete'],
        ];
    }
}
