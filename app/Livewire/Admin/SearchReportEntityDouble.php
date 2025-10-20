<?php

namespace App\Livewire\Admin;

use App\Entity\Repository\DoubleReportEntityRepository;
use App\Livewire\Admin\BaseComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchReportEntityDouble extends BaseComponent
{
    protected $entity;
    protected $fields = [
        'name'      => 'Название',
        'phone'     => 'Телефон',
        'address'   => 'Адрес',
        'email'     => 'Почта',
        'web'       => 'Сайт',
        'vkontakte' => 'Вконтакте',
        'whatsapp'  => 'Whatsapp',
        'telegram'  => 'Telegram',
        'instagram' => 'Instagram',
    ];

    public function __construct()
    {
        $this->entity = new DoubleReportEntityRepository();
        parent::__construct($this->entity);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortField = $field;
            $this->sortAsc = true;
        }
    }

    public function render(Request $request)
    {
        $title = 'Сводка по дублям';
        $table = [];

        foreach ($this->fields as $field => $displayName) {

            $dupAll = DB::table('entities')
                    ->select(DB::raw('SUM(cnt) as total'))
                    ->fromSub(function($q) use ($field) {
                        $q->from('entities')
                            ->select($field, DB::raw('COUNT(*) as cnt'))
                            ->whereNotNull($field)
                            ->where($field, '<>', '')
                            ->groupBy($field)
                            ->havingRaw('COUNT(*) > 1');
                    }, 'sub')
                    ->value('total') ?? 0;

            $dupRegion = DB::table('entities')
                    ->select(DB::raw('SUM(cnt) as total'))
                    ->fromSub(function($q) use ($field) {
                        $q->from('entities')
                            ->select('region_id', $field, DB::raw('COUNT(*) as cnt'))
                            ->whereNotNull($field)
                            ->where($field, '<>', '')
                            ->groupBy('region_id', $field)
                            ->havingRaw('COUNT(*) > 1');
                    }, 'sub')
                    ->value('total') ?? 0;

            $dupCity = DB::table('entities')
                    ->select(DB::raw('SUM(cnt) as total'))
                    ->fromSub(function($q) use ($field) {
                        $q->from('entities')
                            ->select('city_id', $field, DB::raw('COUNT(*) as cnt'))
                            ->whereNotNull($field)
                            ->where($field, '<>', '')
                            ->groupBy('city_id', $field)
                            ->havingRaw('COUNT(*) > 1');
                    }, 'sub')
                    ->value('total') ?? 0;

            $dupValues = DB::table('entities')
                ->select($field, DB::raw('COUNT(*) as cnt'))
                ->whereNotNull($field)
                ->where($field, '<>', '')
                ->groupBy($field)
                ->havingRaw('COUNT(*) > 1')
                ->pluck($field);

            $table[] = [
                'field'  => $displayName,
                'db'     => $dupAll,
                'region' => $dupRegion,
                'city'   => $dupCity,
                'double' => $field,
            ];
        }

        if (in_array($this->sortField, ['field', 'db', 'region', 'city'])) {
            $table = collect($table)->sortBy(function($row) {
                $val = $row[$this->sortField];

                if ($this->sortField === 'field') {
                    return $this->sortAsc
                        ? mb_strtolower($val)
                        : -strcmp($val, '');
                } else {
                    return $this->sortAsc
                        ? $val
                        : -$val;
                }
            })->values()->toArray();
        }

        return view('livewire.admin.search-report-entity-double', [
            'table' => $table,
            'title' => $title,
        ]);
    }
}
