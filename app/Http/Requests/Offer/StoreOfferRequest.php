<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'address'   => ['nullable', 'string', 'max:128'],
            'city'      => ['nullable', 'integer'],
            'user'      => ['nullable', 'integer'],
            'category'  => ['required'],
            'entity'    => ['nullable', 'integer'],
            'activity'  => ['nullable', 'in:1'],
            'images'        => ['nullable', 'array', 'max:20'],
            'images.*'      => ['image', 'mimes:jpg,bmp,png', 'max:20480'],
        ];
    }
}
