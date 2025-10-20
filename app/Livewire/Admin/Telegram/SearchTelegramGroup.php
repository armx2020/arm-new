<?php

namespace App\Livewire\Admin\Telegram;

use App\Entity\Repository\Admin\Telegram\TelegramGroupRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\TelegramGroup;
use App\Models\User;

class SearchTelegramGroup extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new TelegramGroupRepository;
        parent::__construct($this->entity);
    }

    public function changeActivity($id)
    {
        $entity = TelegramGroup::find($id);
        $isActive = $entity->activity ? false : true;

        $entity->update(['activity' => $isActive]);
    }


    public function render()
    {
        $title = 'Все группы в телеграм';
        $emptyEntity = 'групп нет';
        $entityName = 'telegram_group';

        $entities = TelegramGroup::query();

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
            'livewire.admin.telegram.search-telegram-group',
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
