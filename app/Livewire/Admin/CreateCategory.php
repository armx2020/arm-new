<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\EntityType;
use Livewire\Component;

class CreateCategory extends Component
{
    public $selectedType = null;

    public function render()
    {
        $categories = Category::query()->active()->main();

        if ($this->selectedType) {
            $categories = $categories->where('entity_type_id', $this->selectedType)->get();
        } else {
            $categories = $categories->take(0)->get();
        }

        $typies = EntityType::get();

        return view('livewire.admin.create-category', [
            'categories' => $categories,
            'typies' => $typies
        ]);
    }
}
