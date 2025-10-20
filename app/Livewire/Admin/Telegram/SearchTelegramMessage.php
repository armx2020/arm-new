<?php

namespace App\Livewire\Admin\Telegram;

use App\Entity\Repository\Admin\Telegram\TelegramMessageRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\TelegramMessage;

class SearchTelegramMessage extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new TelegramMessageRepository;
        parent::__construct($this->entity);
    }

    public function changeActivity($id)
    {
    }

    public function render()
    {
        $title = 'Все сообщения в телеграм';
        $emptyEntity = 'сообщений нет';
        $entityName = 'telegram_message';

        $entities = TelegramMessage::query();

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
            'livewire.admin.telegram.search-telegram-message',
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
