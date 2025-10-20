<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\AppealRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\Appeal;

class SearchAppeal extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new AppealRepository;
        parent::__construct($this->entity);
    }

    public function changeActivity($id)
    {
        $entity = Appeal::find($id);
        $isActive = $entity->activity ? false : true;

        $entity->update(['activity' => $isActive]);
    }

    public function render()
    {
        $title = 'Все обращения';
        $emptyEntity = 'Сообщений нет';
        $entityName = 'appeal';

        sleep(0.5);
        $entities = Appeal::query()->with('entity', 'user');

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

        return view('livewire.admin.search-appeal', [
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
