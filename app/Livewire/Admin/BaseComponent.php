<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseComponent extends Component
{
    use WithPagination;

    public $term = "";
    public $allColumns = [];
    public $selectedColumns = [];
    public $filters = [];
    public $selectedFilters = [];
    public $sortField = 'id';
    public $sortAsc = false;

    protected $quantityOfDisplayed = 100; // Количество отоброжаемых сущностей

    public function __construct($entity)
    {
        $this->allColumns = $entity->getAllColumns();
        $this->selectedColumns = $entity->getSelectedColumns();
        $this->filters = $entity->getFilters();
        $this->selectedFilters = $entity->getSelectedFilters();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
}
