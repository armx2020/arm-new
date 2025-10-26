<?php

namespace App\Entity\Repository\Admin\Telegram;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class TelegramMessageRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'group_id',
        'user_id',
        'description',
        'text',
        'date',
        'created_at',
        'updated_at',
    ];

    protected $selectedColumns = [
        'id',
        'group_id',
        'user_id',
        'description',
        'text',
        'date',
        'created_at',
        'updated_at',
    ];

    protected $filters = [
        'group_id' => 'relation',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    protected $selectedFilters = [];

    public function getAllColumns(): array
    {
        return $this->allColumns;
    }

    public function getSelectedColumns(): array
    {
        return $this->selectedColumns;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getSelectedFilters(): array
    {
        return $this->selectedFilters;
    }
}
