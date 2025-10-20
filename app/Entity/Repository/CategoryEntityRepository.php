<?php

namespace App\Entity\Repository;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class CategoryEntityRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'category_id',
        'main_category_id',
        'entity_id',
        'created_at',
        'updated_at',
    ];

    protected $selectedColumns = [
        'id',
        'category_id',
        'main_category_id',
        'entity_id',
        'created_at',
        'updated_at',
    ];

    protected $filters = [
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
