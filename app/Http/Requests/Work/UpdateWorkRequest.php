<?php

namespace App\Http\Requests\Work;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'address'       => ['nullable', 'max:128'],
            'description'   => ['nullable', 'string'],
            'city'          => ['integer'],
            'parent'        => [], // TODO доделать
            'type'          => [] // TODO доделать
        ];
    }
}
