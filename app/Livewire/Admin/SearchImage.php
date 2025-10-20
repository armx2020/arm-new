<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\ImageRepository;
use App\Livewire\Admin\BaseComponent;
use App\Models\Image;
use App\Models\Scopes\CheckedScope;
use App\Models\Scopes\SortAscScope;

class SearchImage extends BaseComponent
{
    protected $entity;

    public function __construct()
    {
        $this->entity = new ImageRepository;
        parent::__construct($this->entity);
    }

    public function changeActivity($id)
    {
        $entity = Image::withOutGlobalScopes([SortAscScope::class, CheckedScope::class])->find($id);
        $isActive = $entity->checked ? false : true;

        $entity->update(['checked' => $isActive]);
    }

    public function render()
    {
        $title = 'Реестр изображений';
        $emptyEntity = 'Изображений нет';
        $entityName = 'image';

        sleep(0.5);
        $entities = Image::query()->withOutGlobalScopes([SortAscScope::class, CheckedScope::class])->with('imageable');

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

        return view('livewire.admin.search-image', [
            'entities' => $entities,
            'allColumns' => $this->allColumns,
            'selectedColumns' => $this->selectedColumns,
            'filters' => $this->filters,
            'title' => $title,
            'emptyEntity' => $emptyEntity,
            'entityName' => $entityName,
        ]);
    }
}
