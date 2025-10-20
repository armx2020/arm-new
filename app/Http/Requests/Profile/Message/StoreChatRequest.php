<?php

namespace App\Http\Requests\Profile\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'  => ['nullable', 'required_without:entity_id', 'exists:users,id'],
            'entity_id'  => ['nullable', 'required_without:user_id', 'exists:entities,id'],
        ];
    }
}
