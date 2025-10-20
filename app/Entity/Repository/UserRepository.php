<?php

namespace App\Entity\Repository;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class UserRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'firstname',
        'email',
        'phone',
        'last_active_at',
        'activity',
        'created_at',
        'updated_at',
        'viber',
        'whatsapp',
        'instagram',
        'vkontakte',
        'telegram',
        'city_id',
        'region_id',
    ];

    protected $selectedColumns = [
        'id',
        'firstname',
        'email',
        'phone',
        'city_id',
        'region_id',
    ];

    protected $filters = [
        'created_at' => 'date',
        'updated_at' => 'date',
        'last_active_at' => 'date',
        'activity' => 'bool',
        'city_id' => 'select',
        'region_id' => 'select',
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
