<?php

namespace App\Entity\Repository;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class OfferRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'name',
        'phone',
        'description',
        'activity',
        'created_at',
        'updated_at',
        'viber',
        'whatsapp',
        'instagram',
        'vkontakte',
        'telegram',
        'category_id',
        'entity_id',
        'user_id',
        'city_id',
        'region_id',
    ];

    protected $selectedColumns = [
        'id',
        'name',
        'phone',
        'category_id',
        'entity_id',
        'user_id',
        'city_id',
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
