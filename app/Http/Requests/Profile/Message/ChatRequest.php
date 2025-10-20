<?php

namespace App\Http\Requests\Profile\Message;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'chat'  => ['nullable', 'uuid', 'exists:chats,uuid'],
        ];
    }
}
