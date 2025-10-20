<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\ReportEntityRepository;
use App\Livewire\Admin\BaseComponent;
use Illuminate\Http\Request;
use App\Models\EntityType;
use App\Models\Region;
use App\Models\Entity;

class SearchReportEntity extends BaseComponent
{
    protected $entity;
    public $sortRegionId = null;


    public function __construct()
    {
        $this->entity = new ReportEntityRepository();
        parent::__construct($this->entity);
    }

    public function sortBy($field, $regionId = null)
    {
        if ($regionId !== null) {
            if ($this->sortField === $field && $this->sortRegionId === $regionId) {
                $this->sortAsc = !$this->sortAsc;
            } else {
                $this->sortField = $field;
                $this->sortAsc = true;
                $this->sortRegionId = $regionId;
            }
        } elseif ($field === 'total' || $field === 'region') {
            if ($this->sortField === $field) {
                $this->sortAsc = !$this->sortAsc;
            } else {
                $this->sortField = $field;
                $this->sortAsc = true;
            }
            $this->sortRegionId = null;
        } else {
            if ($this->sortField === $field && $this->sortRegionId === null) {
                $this->sortAsc = !$this->sortAsc;
            } else {
                $this->sortField = $field;
                $this->sortAsc = true;
                $this->sortRegionId = null;
            }
        }
    }

    public function render(Request $request)
    {
        $title = 'Сводка сущности';
        $regions = Region::query()->get();
        $entityTypes = EntityType::query()->get();
        $entityCounts = Entity::query();

        if ($this->term == "") {
            foreach ($this->selectedFilters as $filterName => $filterValue) {
                if ($filterValue) {
                    $operator = array_key_first($filterValue);
                    $callable = $filterValue[$operator];
                    if ($callable != '') {
                        $entityCounts = $entityCounts->where($filterName, $operator, $callable);
                    }
                }
            }
        } else {
            $entityCounts = $entityCounts->search($this->term);
        }

        $entityCounts = $entityCounts
            ->select('region_id', 'entity_type_id', \DB::raw('COUNT(*) as count'))
            ->groupBy('region_id', 'entity_type_id')
            ->get()
            ->groupBy('region_id');

        $table = [];
        $totals = [];
        $regionValues = [];
        $columnTotals = [];

        foreach ($regions as $region) {
            $row = [
                'region' => ['id' => $region->id, 'name' => $region->name],
                'population' => $region->population, // Добавляем численность
                'total' => 0
            ];
            $regionCounts = $entityCounts->get($region->id, collect());

            foreach ($entityTypes as $type) {
                $count = $regionCounts->firstWhere('entity_type_id', $type->id)->count ?? 0;
                $row[$type->name] = [
                    'id' => $type->id,
                    'count' => $count
                ];

                if ($this->sortRegionId === $region->id) {
                    $regionValues[$type->id] = $count;
                }

                if (!isset($columnTotals[$type->name])) {
                    $columnTotals[$type->name] = 0;
                }
                $columnTotals[$type->name] += $count;

                $row['total'] += $count;
            }
            $table[] = $row;
        }

        $totalsRow = [
            'region' => ['id' => null, 'name' => 'Итоги'],
            'population' => null,
            'total' => array_sum($columnTotals)
        ];
        foreach ($entityTypes as $type) {
            $totalsRow[$type->name] = [
                'id' => $type->id,
                'count' => $columnTotals[$type->name] ?? 0
            ];
        }

        $tableWithoutTotals = array_filter($table, function ($row) {
            return $row['region']['name'] !== 'Итоги';
        });

        if ($this->sortField === 'region') {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function ($row) {
                return $this->sortAsc ? strtolower($row['region']['name']) : -strcasecmp($row['region']['name'], '');
            })->values()->toArray();
        } elseif ($this->sortField === 'population') {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function ($row) {
                return $this->sortAsc ? $row['population'] : -$row['population'];
            })->values()->toArray();
        } elseif ($this->sortField === 'total') {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function ($row) {
                return $this->sortAsc ? $row['total'] : -$row['total'];
            })->values()->toArray();
        } elseif ($this->sortRegionId === null) {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function ($row) {
                return $this->sortAsc ? ($row[$this->sortField]['count'] ?? 0) : -($row[$this->sortField]['count'] ?? 0);
            })->values()->toArray();
        }

        if ($this->sortRegionId !== null) {
            $entityTypes = $entityTypes->sortBy(function ($type) use ($regionValues) {
                return $this->sortAsc ? ($regionValues[$type->id] ?? 0) : -($regionValues[$type->id] ?? 0);
            });
        }

        $tableWithoutTotals[] = $totalsRow;

        return view('livewire.admin.search-report-entity', [
            'entityTypes' => $entityTypes,
            'regions' => $regions,
            'table' => $tableWithoutTotals,
            'title' => $title
        ]);
    }

}
