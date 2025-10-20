<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Entity;
use Livewire\Component;

class EditOffer extends Component
{
    public $selectedType = null;
    public $offer = null;

    public function mount($offer)
    {
        $this->offer = $offer;
        $this->selectedType = $offer->entity_id;
    }

    public function render()
    {
        $categories = Category::query()->active()->with('categories')->where('category_id', null);

        if ($this->selectedType) {
            $entity = Entity::where('id', $this->selectedType)->First();
            $categories = $categories->where('entity_type_id', $entity->entity_type_id)->get();
        } else {
            $categories = $categories->take(0)->get();
        }

        $entities = Entity::whereNotNull('entity_type_id')->get();

        return view(
            'livewire.admin.edit-offer',
            [
                'categories' => $categories,
                'entities' => $entities
            ]
        );
    }
}
