<?php

namespace App\Entity\Repository;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class EventRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'name',
        'date_to_start',
        'description',
        'address',
        'category_id',
        'activity',
        'created_at',
        'updated_at',
        'city_id',
        'region_id',
        'parent_type',
        'parent_id',
    ];

    protected $selectedColumns = [
        'id',
        'name',
        'date_to_start',
        'type',
        'category_id',
        'activity',
    ];

    protected $filters = [
        'created_at' => 'date',
        'updated_at' => 'date',
        'date_to_start' => 'date',
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
