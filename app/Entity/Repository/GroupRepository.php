<?php

namespace App\Entity\Repository;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class GroupRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'created_at',
        'updated_at',
        'name',
        'activity',
        'address',
        'description',
        'phone',
        'web',
        'viber',
        'whatsapp',
        'instagram',
        'vkontakte',
        'telegram',
        'user_id',
        'city_id',
        'region_id',
        'category_id',
        'comment'

    ];

    protected $selectedColumns = [
        'id',
        'name',
        'address',
        'phone',
        'user_id',
        'city_id',
        'category_id',
        'region_id',
    ];

    protected $filters = [
        'created_at' => 'date',
        'updated_at' => 'date',
        'activity' => 'bool',
        //    'user_id' => 'select', // TODO выборка по пользователю
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
