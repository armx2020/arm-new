<?php

namespace App\Http\Requests\Admin\Telegram;

use Illuminate\Foundation\Http\FormRequest;

class TelegramGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'          => ['required', 'string', 'max:255'],
        ];
    }
}
