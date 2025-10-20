<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\EntityType;
use Livewire\Component;

class CreateEntity extends Component
{
    public $selectedType = null;

    public function render()
    {
        $categories = Category::query()->active()->with('categories')->where('category_id', null);

        if ($this->selectedType) {
            $categories = $categories->where('entity_type_id', $this->selectedType)->get();
        } else {
            $categories = $categories->take(0)->get();
        }

        $typies = EntityType::active()->get();

        return view(
            'livewire.admin.create-entity',
            [
                'typies' => $typies,
                'categories' => $categories,
            ]
        );
    }
}
