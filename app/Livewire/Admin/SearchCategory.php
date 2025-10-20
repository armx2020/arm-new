<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\CategoryRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\Category;

class SearchCategory extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new CategoryRepository;
        parent::__construct($this->entity);
    }

    public function changeActivity($id)
    {
        $entity = Category::find($id);
        $isActive = $entity->activity ? false : true;

        $entity->update(['activity' => $isActive]);
    }

    public function render()
    {
        $title = 'Все категории';
        $emptyEntity = 'Категорий нет';
        $entityName = 'category';

        sleep(0.5);
        $entities = Category::query()->with('type');

        if ($this->term == "") {
            foreach ($this->selectedFilters as $filterName => $filterValue) {
                $operator = array_key_first($filterValue);
                $callable = $filterValue[array_key_first($filterValue)];

                $entities = $entities->where($filterName, $operator, $callable);
            }
        } else {
            $entities = $entities->search($this->term);
        }

        $entities = $entities->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->quantityOfDisplayed);

        return view('livewire.admin.search-category', [
            'entities' => $entities,
            'allColumns' => $this->allColumns,
            'selectedColumns' => $this->selectedColumns,
            'filters' => $this->filters,
            'title' => $title,
            'emptyEntity' => $emptyEntity,
            'entityName' => $entityName,
        ]);
    }
}
