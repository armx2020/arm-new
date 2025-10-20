<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\EntityType;
use App\Models\Entity;
use Livewire\Component;

class EditEntity extends Component
{
    public $selectedType = null;
    public $entity = null;

    public function mount($entity)
    {
        $this->entity = $entity;
        $this->selectedType = $entity->entity_type_id;
    }

    public function render()
    {
        $fields = ['name', 'phone', 'address', 'email', 'web', 'vkontakte', 'whatsapp', 'telegram', 'instagram'];

        $duplicateExists = Entity::where(function ($query) use ($fields) {
            foreach ($fields as $field) {
                if (!empty($this->entity->$field)) {
                    $query->orWhere($field, $this->entity->$field);
                }
            }
        })->where('id', '!=', $this->entity->id)
            ->exists();
        $categories = Category::query()->active()->with('categories')->where('category_id', null);

        if ($this->selectedType) {
            $categories = $categories->where('entity_type_id', $this->selectedType)->get();
        } else {
            $categories = $categories->take(0)->get();
        }

        $typies = EntityType::active()->get();

        return view(
            'livewire.admin.edit-entity',
            [
                'typies' => $typies,
                'categories' => $categories,
                'duplicateExists' => $duplicateExists
            ]
        );
    }
}
