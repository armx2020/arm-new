<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\CategoryReportEntityRepository;
use App\Livewire\Admin\BaseComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EntityType;
use App\Models\Region;
use App\Models\Entity;
use App\Models\Category;

class SearchReportEntityCategory extends BaseComponent
{
    protected $entity;
    public $sortRegionId = null;

    public function __construct()
    {
        $this->entity = new CategoryReportEntityRepository();
        parent::__construct($this->entity);
    }

    public function sortBy($field, $regionId = null)
    {
        if ($regionId !== null) {
            if ($this->sortField === $field && $this->sortRegionId === $regionId) {
                $this->sortAsc = ! $this->sortAsc;
            } else {
                $this->sortField = $field;
                $this->sortAsc = true;
                $this->sortRegionId = $regionId;
            }
        }
        elseif (in_array($field, ['total', 'region', 'population', 'total_entities'])) {
            if ($this->sortField === $field) {
                $this->sortAsc = ! $this->sortAsc;
            } else {
                $this->sortField = $field;
                $this->sortAsc = true;
            }
            $this->sortRegionId = null;
        }
        else {
            if ($this->sortField === $field && $this->sortRegionId === null) {
                $this->sortAsc = ! $this->sortAsc;
            } else {
                $this->sortField = $field;
                $this->sortAsc = true;
                $this->sortRegionId = null;
            }
        }
    }

    public function render(Request $request)
    {
        $title = 'Сводка по категориям';
        $regions = Region::all();

        $categoriesQuery = Category::query();
        if (!empty($this->selectedFilters['entity_type_id']['='])) {
            $categoriesQuery->where('entity_type_id', $this->selectedFilters['entity_type_id']['=']);
        }
        $categories = $categoriesQuery->get();

        $entityQuery = Entity::query();
        $activity = isset($this->selectedFilters['activity']['=']) && $this->selectedFilters['activity']['='] != '';

        if (!empty($this->selectedFilters['entity_type_id']['='])) {
            $entityQuery->where('entity_type_id', $this->selectedFilters['entity_type_id']['=']);
        }
        if ($activity) {
            $entityQuery->where('activity', (bool)$this->selectedFilters['activity']['=']);
        }

        $entityCounts = $entityQuery
            ->select('region_id', DB::raw('COUNT(*) as total_entities'))
            ->groupBy('region_id')
            ->get()
            ->keyBy('region_id');

        $categoryQuery = Entity::query();
        if (!empty($this->selectedFilters['entity_type_id']['='])) {
            $categoryQuery->where('entity_type_id', $this->selectedFilters['entity_type_id']['=']);
        }
        if ($activity) {
            $categoryQuery->where('activity', (bool)$this->selectedFilters['activity']['=']);
        }

        $categoryCounts = $categoryQuery
            ->select('region_id', 'category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('region_id', 'category_id')
            ->get()
            ->groupBy('region_id');

        $table = [];
        $totals = [];

        foreach ($regions as $region) {
            $row = [
                'region' => ['id' => $region->id, 'name' => $region->name],
                'population' => $region->population,
                'total_entities' => $entityCounts[$region->id]->total_entities ?? 0,
                'total' => 0,
            ];

            $regionCatCounts = $categoryCounts->get($region->id, collect());

            foreach ($categories as $cat) {
                $count = $regionCatCounts->firstWhere('category_id', $cat->id)->count ?? 0;
                $row[$cat->name] = $count;
                $row['total'] += $count;

                if (!isset($totals[$cat->name])) {
                    $totals[$cat->name] = 0;
                }
                $totals[$cat->name] += $count;
            }

            $table[] = $row;
        }

        $totalsRow = [
            'region' => ['id' => null, 'name' => 'Итоги'],
            'population' => null,
            'total_entities' => array_sum(array_column($table, 'total_entities')),
            'total' => array_sum($totals),
        ];

        foreach ($categories as $cat) {
            $totalsRow[$cat->name] = $totals[$cat->name] ?? 0;
        }

        $tableWithoutTotals = array_filter($table, fn($r) => $r['region']['name'] !== 'Итоги');

        if ($this->sortField === 'region') {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function($row){
                return $this->sortAsc
                    ? mb_strtolower($row['region']['name'])
                    : -strcmp($row['region']['name'], '');
            })->values()->toArray();
        }
        elseif ($this->sortField === 'population') {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function($row){
                return $this->sortAsc
                    ? ($row['population'] ?? 0)
                    : -($row['population'] ?? 0);
            })->values()->toArray();
        }
        elseif ($this->sortField === 'total_entities') {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function($row){
                return $this->sortAsc
                    ? $row['total_entities']
                    : -$row['total_entities'];
            })->values()->toArray();
        }
        elseif ($this->sortField === 'total') {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function($row){
                return $this->sortAsc
                    ? $row['total']
                    : -$row['total'];
            })->values()->toArray();
        }
        elseif ($this->sortRegionId === null) {
            $tableWithoutTotals = collect($tableWithoutTotals)->sortBy(function($row){
                return $this->sortAsc
                    ? ($row[$this->sortField] ?? 0)
                    : -($row[$this->sortField] ?? 0);
            })->values()->toArray();
        }

        if ($this->sortRegionId !== null) {
            $regionRow = collect($tableWithoutTotals)->firstWhere('region.id', $this->sortRegionId);
            if ($regionRow) {
                $vals = [];
                foreach ($categories as $cat) {
                    $vals[$cat->name] = $regionRow[$cat->name] ?? 0;
                }

                $categories = $categories->sortBy(function ($cat) use ($vals) {
                    return $this->sortAsc
                        ? $vals[$cat->name]
                        : -$vals[$cat->name];
                })->values();
            }
        }

        $tableWithoutTotals[] = $totalsRow;

        return view('livewire.admin.search-report-entity-category', [
            'categories' => $categories,
            'table' => $tableWithoutTotals,
            'title' => $title,
        ]);
    }
}
