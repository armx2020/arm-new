<?php

namespace App\Entity\Repository;

use App\Contracts\EntityColumnsInterface;
use App\Contracts\EntityFiltersInterface;

class ImageRepository implements EntityColumnsInterface, EntityFiltersInterface
{
    protected $allColumns = [
        'id',
        'sort_id',
        'imageable_type',
        'imageable_id',
        'path',
        'checked',
        'created_at',
        'updated_at',
    ];

    protected $selectedColumns = [
        'id',
        'sort_id',
        'imageable_type',
        'imageable_id',
        'path',
        'checked',
    ];

    protected $filters = [
        'created_at' => 'date',
        'updated_at' => 'date',
        'checked' => 'bool',
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
