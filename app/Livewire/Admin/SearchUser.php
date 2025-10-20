<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\UserRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\User;

class SearchUser extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new UserRepository;
        parent::__construct($this->entity);
    }

    public function changeActivity($id)
    {
        $entity = User::find($id);
        $isActive = $entity->activity ? false : true;

        $entity->update(['activity' => $isActive]);
    }

    public function changeRole($id)
    {
        $entity = User::find($id);
        if ($entity->hasRole('moderator')) {
            $entity->removeRole('moderator');
        } else {
            $entity->assignRole('moderator');
        }
    }

    public function render()
    {
        $title = 'Все пользователи';
        $emptyEntity = 'Пользователей нет';
        $entityName = 'user';

        $entities = User::query()->with('city');

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

        return view(
            'livewire.admin.search-user',
            [
                'entities' => $entities,
                'allColumns' => $this->allColumns,
                'selectedColumns' => $this->selectedColumns,
                'filters' => $this->filters,
                'title' => $title,
                'emptyEntity' => $emptyEntity,
                'entityName' => $entityName,
            ]
        );
    }
}
