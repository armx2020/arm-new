<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Entity;
use App\Models\EntityType;
use App\Models\Page;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;

class PageCount extends Command
{
    protected $signature = 'app:page-count {--truncate}';

    protected $description = 'подсчёт страниц';

    public function handle()
    {
        if($this->option('truncate')) {
            Page::truncate();
        }

        $entity_types = EntityType::with('categories')->active()->get();

        $count = 1;

        // Главные страницы регионов
        Page::updateOrCreate(
            [
                'url' => 'https://vsearmyane.ru'
            ],
            [
                'site_type_id' => 1,
                'title' => null,
                'description' => null,
                'quantity_entity' => null,
                'index' => true
            ]
        );

        foreach ($entity_types as $type) {

            $count = Entity::active()->where('entity_type_id', $type->id)->count();

            Page::updateOrCreate(
                [
                    'url' => 'https://vsearmyane.ru' . '/' . $type->transcription
                ],
                [
                    'site_type_id' => 4,
                    'title' => null,
                    'description' => null,
                    'quantity_entity' => $count,
                    'index' => true
                ]
            );

            foreach ($type->categories as $category) {
                if ($category->activity) {

                    $count = Entity::active()
                        ->where('entity_type_id', $type->id)
                        ->where(function (Builder $query) use ($category) {
                            $query
                                ->where('category_id', $category->id)
                                ->orWhereHas('fields', function ($que) use ($category) {
                                    $que->where('category_entity.main_category_id', '=', $category->id);
                                });
                        })->count();

                    Page::updateOrCreate(
                        [
                            'url' => 'https://vsearmyane.ru' . '/' . $type->transcription . '/' . $category->transcription
                        ],
                        [
                            'site_type_id' => 5,
                            'title' => null,
                            'description' => null,
                            'quantity_entity' => null,
                            'index' => true
                        ]
                    );
                }
            }
        }

        // Страницы регионов
        Region::chunk(50, function (Collection $regions) use ($entity_types) {

            foreach ($regions as $region) {
                if ($region->id !== 1) {

                    $count = null;

                    Page::updateOrCreate(
                        [
                            'url' => 'https://vsearmyane.ru' .'/'. $region->transcription
                        ],
                        [
                            'site_type_id' => 2,
                            'title' => null,
                            'description' => null,
                            'quantity_entity' => null,
                            'index' => true
                        ]
                    );

                    // Типы сущностей в регионе
                    foreach ($entity_types as $type) {

                        $count = Entity::active()
                            ->where('entity_type_id', $type->id)
                            ->where('region_id', $region->id)
                            ->count();

                        Page::updateOrCreate(
                            [
                                'url' => 'https://vsearmyane.ru' .'/'. $region->transcription . '/' . $type->transcription
                            ],
                            [
                                'site_type_id' => 4,
                                'title' => null,
                                'description' => null,
                                'quantity_entity' => $count,
                                'index' => true
                            ]
                        );

                        // Категории сущностей в регионе
                        foreach ($type->categories as $category) {
                            if ($category->activity) {

                                $count = Entity::active()
                                    ->where('entity_type_id', $type->id)
                                    ->where('region_id', $region->id)
                                    ->where(function (Builder $query) use ($category) {
                                        $query
                                            ->where('category_id', $category->id)
                                            ->orWhereHas('fields', function ($que) use ($category) {
                                                $que->where('category_entity.main_category_id', '=', $category->id);
                                            });
                                    })->count();

                                Page::updateOrCreate(
                                    [
                                        'url' => 'https://vsearmyane.ru' .'/'. $region->transcription . '/' . $type->transcription . '/' . $category->transcription
                                    ],
                                    [
                                        'site_type_id' => 5,
                                        'title' => null,
                                        'description' => null,
                                        'quantity_entity' => $count,
                                        'index' => true
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        });

        // Страницы городов
        City::chunk(50, function (Collection $cities) use ($entity_types) {
            foreach ($cities as $city) {

                if ($city->id !== 1) {

                    $count = null;

                    Page::updateOrCreate(
                        [
                            'url' => 'https://vsearmyane.ru' .'/'. $city->transcription
                        ],
                        [
                            'site_type_id' => 3,
                            'title' => null,
                            'description' => null,
                            'quantity_entity' => null,
                            'index' => true
                        ]
                    );

                    foreach ($entity_types as $type) {

                        $count = Entity::active()
                            ->where('entity_type_id', $type->id)
                            ->where('region_id', $city->region->id)
                            ->count();

                        Page::updateOrCreate(
                            [
                                'url' => 'https://vsearmyane.ru' .'/'. $city->transcription . '/' . $type->transcription
                            ],
                            [
                                'site_type_id' => 4,
                                'title' => null,
                                'description' => null,
                                'quantity_entity' => $count,
                                'index' => true
                            ]
                        );

                        foreach ($type->categories as $category) {
                            if ($category->activity) {

                                $count = Entity::active()
                                    ->where('entity_type_id', $type->id)
                                    ->where('region_id', $city->region->id)
                                    ->where(function (Builder $query) use ($category) {
                                        $query
                                            ->where('category_id', $category->id)
                                            ->orWhereHas('fields', function ($que) use ($category) {
                                                $que->where('category_entity.main_category_id', '=', $category->id);
                                            });
                                    })->count();

                                Page::updateOrCreate(
                                    [
                                        'url' => 'https://vsearmyane.ru' .'/'. $city->transcription . '/' . $type->transcription . '/' . $category->transcription
                                    ],
                                    [
                                        'site_type_id' => 5,
                                        'title' => null,
                                        'description' => null,
                                        'quantity_entity' => $count,
                                        'index' => true
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        });

        // Страницы сущности
        Entity::chunk(50, function (Collection $entities) {
            foreach ($entities as $entity) {
                if ($entity->activity) {

                    $type = 'company';

                    switch ($entity->type->id) {
                        case 1:
                            $type = 'company';
                            break;
                        case 2:
                            $type = 'group';
                            break;
                        case 3:
                            $type = 'place';
                            break;
                        case 4:
                            $type = 'community';
                            break;
                    }

                    Page::updateOrCreate(
                        [
                            'url' => 'https://vsearmyane.ru' . '/' . $type . '/' . $entity->id
                        ],
                        [
                            'site_type_id' => 6,
                            'title' => null,
                            'description' => null,
                            'quantity_entity' => null,
                            'index' => true
                        ]
                    );

                    Page::updateOrCreate(
                        [
                            'url' => 'https://vsearmyane.ru' . '/' . $type . '/' . $entity->transcription
                        ],
                        [
                            'site_type_id' => 6,
                            'title' => null,
                            'description' => null,
                            'quantity_entity' => null,
                            'index' => true
                        ]
                    );
                }
            }
        });
    }
}
