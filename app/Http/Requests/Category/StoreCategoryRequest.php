<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'sort_id'        => ['required'],
            'type'           => ['in:group,event,offer'], // TODO удалить после переноса сущностей
            'entity_type_id' => ['integer'],
            'image'          => ['nullable', 'image', 'mimes:jpg,bmp,png', 'max:20000'],
            'image_remove'   => ['nullable', 'in:delete'],
            'parent'         => ['nullable', 'integer']
        ];
    }
}
