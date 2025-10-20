<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\CategoryEntityRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\CategoryEntity;

class SearchCategoryEntity extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new CategoryEntityRepository;
        parent::__construct($this->entity);
    }

    public function render()
    {
        $title = 'Связь категория-сущность';
        $emptyEntity = 'Связей нет';
        $entityName = 'category-entity';

        sleep(0.5);
        $entities = CategoryEntity::query()->with('category', 'entity');

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

        return view('livewire.admin.search-category-entity', [
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
