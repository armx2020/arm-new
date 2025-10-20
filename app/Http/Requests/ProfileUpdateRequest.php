<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'firstname' => ['string', 'max:32'],
            'email'     => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'viber'     => ['max:36'],
            'whatsapp'  => ['max:36'],
            'telegram'  => ['max:36'],
            'instagram' => ['max:36'],
            'vkontakte' => ['max:36'],
        ];
    }
}
