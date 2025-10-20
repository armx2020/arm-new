<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\EntityRepository;
use App\Livewire\Admin\BaseComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Entity;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class SearchEntity extends BaseComponent
{
    protected $entity;

    public $type;
    public $region;
    public $category;
    public $field;
    public $duplicatesField = null;
    public $doubleRegion = false;
    public $doubleCity   = false;
    public $double_id = null;

    protected array $allowedFields = [
        'name',
        'phone',
        'address',
        'email',
        'web',
        'vkontakte',
        'whatsapp',
        'telegram',
        'instagram',
    ];

    public function __construct()
    {
        $this->entity = new EntityRepository();
        parent::__construct($this->entity);
    }

    public function mount(Request $request)
    {
        $this->type           = $request->get('type');
        $this->region         = $request->get('region');
        $this->category       = $request->get('category');
        $this->field          = $request->get('field');
        $this->duplicatesField = $request->get('duplicatesField');
        $this->double_id      = $request->get('double_id');
        $this->doubleRegion   = (bool) $request->get('doubleRegion', false);
        $this->doubleCity     = (bool) $request->get('doubleCity', false);
        if (!in_array($this->duplicatesField, $this->allowedFields, true)) {
            $this->duplicatesField = null;
        }
    }

    public function changeActivity($id)
    {
        $entity = Entity::find($id);
        $entity->update(['activity' => ! $entity->activity]);
    }

    public function render(Request $request)
    {
        if (isset($this->type)) {
            $this->selectedFilters['entity_type_id']['='] = $this->type;
        }
        if (isset($this->region)) {
            $this->selectedFilters['region_id']['='] = $this->region;
        }
        if (isset($this->category)) {
            $this->selectedFilters['category_id']['='] = $this->category;
        }
        if (isset($this->field)) {
            $this->selectedFilters['fields']['='] = $this->category;
        }

        $title       = 'Все сущности';
        $emptyEntity = 'сущностей нет';
        $entityName  = 'entity';

        $entities = Entity::query()->withoutGlobalScopes()->with('city', 'type', 'primaryImage', 'region', 'category', 'user');

        if(Auth::user()->hasRole('moderator')) {
            $entities = $entities->where('moderator_id', Auth::user()->id);
        }

        if ($this->term == "") {
            foreach ($this->selectedFilters as $filterName => $filterValue) {

                if ($filterValue) {

                    $operator = array_key_first($filterValue);
                    $callable = $filterValue[array_key_first($filterValue)];

                    if ($filterName == 'field') {
                        $entities = $entities
                            ->where(function (Builder $query) use ($filterValue) {
                                $query
                                    ->whereHas('fields', function ($que) use ($filterValue) {
                                        $que->where('category_entity.category_id', '=', $filterValue); // ID категории
                                    });
                            });
                    } elseif ($filterName == 'activity' && $callable !== '') {
                        $entities->where($filterName, $operator, $callable);
                    } else {
                        if ($callable) {
                            $entities->where($filterName, $operator, $callable);
                        }
                    }
                }
            }
        } else {
            $entities = $entities->search($this->term);
        }

        if (isset($this->double_id) && $this->double_id !== '') {
            $doubleEntity = Entity::find($this->double_id);
            if ($doubleEntity) {
                $entities->where(function ($query) use ($doubleEntity) {
                    foreach ($this->allowedFields as $field) {
                        if (!empty($doubleEntity->{$field})) {
                            $query->orWhere($field, $doubleEntity->{$field});
                        }
                    }
                });
            }
        }

        // --- Фильтр дублей ---
        if ($this->duplicatesField) {
            if ($this->doubleRegion) {
                $entities->whereIn('id', function ($sub) {
                    $sub->select('e.id')
                        ->distinct()
                        ->from('entities as e')
                        ->join(DB::raw('(
                            SELECT region_id as rid, ' . $this->duplicatesField . ' as val
                            FROM entities
                            WHERE ' . $this->duplicatesField . ' <> ""
                              AND ' . $this->duplicatesField . ' IS NOT NULL
                            GROUP BY region_id, ' . $this->duplicatesField . '
                            HAVING COUNT(*) > 1
                        ) grp'), function ($join) {
                            $join->on('e.region_id', '=', 'grp.rid')
                                ->on('e.' . $this->duplicatesField, '=', 'grp.val');
                        });
                });
            } elseif ($this->doubleCity) {
                $entities->whereIn('id', function ($sub) {
                    $sub->select('e.id')
                        ->distinct()
                        ->from('entities as e')
                        ->join(DB::raw('(
                            SELECT city_id as cid, ' . $this->duplicatesField . ' as val
                            FROM entities
                            WHERE ' . $this->duplicatesField . ' <> ""
                              AND ' . $this->duplicatesField . ' IS NOT NULL
                            GROUP BY city_id, ' . $this->duplicatesField . '
                            HAVING COUNT(*) > 1
                        ) grp'), function ($join) {
                            $join->on('e.city_id', '=', 'grp.cid')
                                ->on('e.' . $this->duplicatesField, '=', 'grp.val');
                        });
                });
            } else {
                $entities->whereIn('id', function ($sub) {
                    $sub->select('e.id')
                        ->distinct()
                        ->from('entities as e')
                        ->join(DB::raw('(
                            SELECT ' . $this->duplicatesField . ' as val
                            FROM entities
                            WHERE ' . $this->duplicatesField . ' <> ""
                              AND ' . $this->duplicatesField . ' IS NOT NULL
                            GROUP BY ' . $this->duplicatesField . '
                            HAVING COUNT(*) > 1
                        ) grp'), function ($join) {
                            $join->on('e.' . $this->duplicatesField, '=', 'grp.val');
                        });
                });
            }
        } else {
            $entities->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
        }

        $entities = $entities->paginate($this->quantityOfDisplayed);

        $colorMap = [];
        if ($this->duplicatesField) {
            $items = collect($entities->items());

            $groups = $items->groupBy(function ($e) {
                $key = $e->{$this->duplicatesField} ?: '';
                if ($this->doubleRegion) {
                    $key .= '|region=' . $e->region_id;
                }
                if ($this->doubleCity) {
                    $key .= '|city=' . $e->city_id;
                }
                return $key;
            });

            $groups = $groups->filter(function ($grp) {
                return $grp->count() > 1;
            });

            $sortedItems = collect();
            foreach ($groups as $g) {
                $sortedItems = $sortedItems->merge($g);
            }

            $entities->setCollection($sortedItems);

            $colors = ['bg-gray-300', 'bg-gray-200'];
            $index = 0;
            foreach ($groups as $grp) {
                $twClass = $colors[$index % count($colors)];
                foreach ($grp as $entityItem) {
                    $colorMap[$entityItem->id] = $twClass;
                }
                $index++;
            }
        }

        return view('livewire.admin.search-entity', [
            'entities'    => $entities,
            'allColumns'  => $this->allColumns,
            'selectedColumns' => $this->selectedColumns,
            'filters'     => $this->filters,
            'title'       => $title,
            'emptyEntity' => $emptyEntity,
            'entityName'  => $entityName,
            'colorMap'    => $colorMap,
        ]);
    }
}
