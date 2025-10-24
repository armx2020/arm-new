<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\TypeRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\EntityType;

class SearchType extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new TypeRepository;
        parent::__construct($this->entity);
    }

    public function changeActivity($id)
    {
        $entity = EntityType::find($id);
        $isActive = $entity->activity ? false : true;

        $entity->update(['activity' => $isActive]);
    }

    public function render()
    {
        $title = 'Все типы сущностей';
        $emptyEntity = 'Типов для сущностей нет';
        $entityName = 'type';

        if ($this->term == "") {

            $entities = EntityType::query();

            foreach ($this->selectedFilters as $filterName => $filterValue) {
                $operator = array_key_first($filterValue);
                $callable = $filterValue[array_key_first($filterValue)];

                $entities = $entities->where($filterName, $operator, $callable);
            }
            $entities = $entities->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->quantityOfDisplayed);
        } else {
            $entities = EntityType::search($this->term)->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->paginate($this->quantityOfDisplayed);
        }

        return view('livewire.admin.search-type', [
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
