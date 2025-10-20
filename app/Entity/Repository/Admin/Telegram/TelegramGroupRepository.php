<?php

namespace App\Entity\Repository\Admin\Telegram;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class TelegramGroupRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'username',
        'title',
        'description',
        'created_at',
        'updated_at',
        'activity'
    ];

    protected $selectedColumns = [
        'id',
        'username',
        'title',
        'description',
        'created_at',
        'updated_at',
        'activity'
    ];

    protected $filters = [
        'created_at' => 'date',
        'updated_at' => 'date',
        'activity' => 'bool',
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
