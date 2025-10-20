<?php

namespace App\Services;

use App\Models\City;
use App\Models\Entity;
use App\Models\EntityType;
use App\Models\Region;
use App\Models\SiteMap;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Sitemap\Sitemap as SM;
use Spatie\Sitemap\SitemapIndex;

class SiteMapService
{
    private $entity_types =
    [
        1 => 'company',
        2 => 'group',
        3 => 'place',
        4 => 'community'
    ];

    private $site_map_types =
    [
        'домашняя (общая)'          => 1,
        'домашняя (область)'        => 2,
        'домашняя (город)'          => 3,
        'тип сущности (общая)'      => 4,
        'тип сущности (область)'    => 5,
        'тип сущности (город)'      => 6,
        'категория (общая)'         => 7,
        'категория (область)'       => 8,
        'категория (город)'         => 9,
        'сущность'                  => 10,
    ];

    public function create()
    {
        // Страницы регионов
        $this->pageForRegions();

        // Страницы городов
        $this->pageForCities();

        // Страницы сущности
        $this->pageForEntities();
    }

    public function pageForRegions()
    {
        $entity_types = EntityType::with('categories')->active()->get();

        Region::chunk(50, function (Collection $regions) use ($entity_types) {

            foreach ($regions as $region) {

                $count = null;

                if ($region->id == 1) {
                    $url = 'https://vsearmyane.ru';
                    $name = 'Армянский справочник для армян России и мира';
                    $description = 'Армянский справочник для армян России и мира';
                    $site_map_type = $this->site_map_types['домашняя (общая)'];
                } else {
                    $url = 'https://vsearmyane.ru'  . '/' . $region->transcription;
                    $name = "Армянский справочник для армян России и мира " . $region->name_dat;
                    $description = "Армянский справочник для армян России и мира " . $region->name_dat;
                    $site_map_type = $this->site_map_types['домашняя (область)'];
                }

                SiteMap::updateOrCreate(
                    [
                        'url' => $url
                    ],
                    [
                        'site_map_type_id' => $site_map_type,
                        'name' => $name,
                        'title' => $name,
                        'description' => $description,
                        'quantity_entity' => null,
                        'region_id' => $region->id,
                        'city_id' => null,
                        'entity_type_id' => null,
                        'category_id' => null,
                        'entity_id' => null,
                        'index' => true
                    ]
                );

                // Типы сущностей в регионе
                foreach ($entity_types as $type) {

                    $name = "Армянские " . mb_strtolower($type->name) . " " . $region->name_dat;
                    $description = "Армянские " . mb_strtolower($type->name) . " " . $region->name_dat;
                    $site_map_type = $this->site_map_types['тип сущности (область)'];


                    $count = Entity::active()
                        ->where('entity_type_id', $type->id)
                        ->where('region_id', $region->id)
                        ->count();

                    SiteMap::updateOrCreate(
                        [
                            'url' => $url . '/' . $type->transcription
                        ],
                        [
                            'site_map_type_id' => $site_map_type,
                            'name' => $name,
                            'title' => $name,
                            'description' => $description,
                            'quantity_entity' => $count,
                            'region_id' => $region->id,
                            'city_id' => null,
                            'entity_type_id' => $type->id,
                            'category_id' => null,
                            'entity_id' => null,
                            'index' => true
                        ]
                    );

                    // Категории сущностей в регионе
                    foreach ($type->categories as $category) {
                        if ($category->activity && $category->category_id == null) {

                            $name = "Армянские " . mb_strtolower($type->name) . " " . $region->name_dat . " " . " - " . mb_strtolower($category->name);
                            $description = "Армянские " . mb_strtolower($type->name) . " " . $region->name_dat . " " . " - " . mb_strtolower($category->name);
                            $site_map_type = $this->site_map_types['категория (область)'];

                            $count = Entity::active()
                                ->where('entity_type_id', $type->id)
                                ->where('region_id', $region->id)
                                ->count();

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

                            SiteMap::updateOrCreate(
                                [
                                    'url' => $url . '/' . $type->transcription . '/' . $category->transcription
                                ],
                                [
                                    'site_map_type_id' => $site_map_type,
                                    'name' => $name,
                                    'title' => $name,
                                    'description' => $description,
                                    'quantity_entity' => $count,
                                    'region_id' => $region->id,
                                    'city_id' => null,
                                    'entity_type_id' => $type->id,
                                    'category_id' => $category->id,
                                    'entity_id' => null,
                                    'index' => true
                                ]
                            );
                        }
                    }
                }
            }
        });
    }

    public function pageForCities()
    {
        $entity_types = EntityType::with('categories')->active()->get();

        City::chunk(50, function (Collection $cities) use ($entity_types) {

            foreach ($cities as $city) {

                $count = null;

                if ($city->id == 1) {
                    $url = 'https://vsearmyane.ru';
                    $name = 'Армянский справочник для армян России и мира';
                    $description = 'Армянский справочник для армян России и мира';
                    $site_map_type = $this->site_map_types['домашняя (общая)'];
                } else {
                    $url = 'https://vsearmyane.ru'  . '/' . $city->transcription;
                    $name = "Армянский справочник для армян России и мира " . $city->name_dat;
                    $description = "Армянский справочник для армян России и мира " . $city->name_dat;
                    $site_map_type = $this->site_map_types['домашняя (город)'];
                }

                SiteMap::updateOrCreate(
                    [
                        'url' => $url
                    ],
                    [
                        'site_map_type_id' => $site_map_type,
                        'name' => $name,
                        'title' => $name,
                        'description' => $description,
                        'quantity_entity' => null,
                        'region_id' => $city->region_id,
                        'city_id' => $city->id,
                        'entity_type_id' => null,
                        'category_id' => null,
                        'entity_id' => null,
                        'index' => true
                    ]
                );

                // Типы сущностей в городе
                foreach ($entity_types as $type) {

                    if ($city->id == 1) {
                        $name =  "Армянские " . mb_strtolower($type->name) . ' в России';
                        $description = "Армянские " . mb_strtolower($type->name) . ' в России';
                        $site_map_type = $this->site_map_types['тип сущности (общая)'];
                    } else {
                        $name = "Армянские " . mb_strtolower($type->name) . " " . $city->name_dat;
                        $description = "Армянские " . mb_strtolower($type->name) . " " . $city->name_dat;
                        $site_map_type = $this->site_map_types['тип сущности (город)'];
                    }

                    $count = Entity::active()
                        ->where('entity_type_id', $type->id)
                        ->where('city_id', $city->id)
                        ->count();

                    SiteMap::updateOrCreate(
                        [
                            'url' => $url . '/' . $type->transcription
                        ],
                        [
                            'site_map_type_id' => $site_map_type,
                            'name' => $name,
                            'title' => $name,
                            'description' => $description,
                            'quantity_entity' => $count,
                            'region_id' => $city->region_id,
                            'city_id' => $city->id,
                            'entity_type_id' => $type->id,
                            'category_id' => null,
                            'entity_id' => null,
                            'index' => true
                        ]
                    );

                    // Категории сущностей в городе
                    foreach ($type->categories as $category) {
                        if ($category->activity && $category->category_id == null) {

                            if ($city->id == 1) {
                                $name =  "Армянские " . mb_strtolower($type->name) . ' в России' . " - " . mb_strtolower($category->name);
                                $description = "Армянские " . mb_strtolower($type->name) . ' в России' . " - " . mb_strtolower($category->name);
                                $site_map_type = $this->site_map_types['категория (общая)'];
                            } else {
                                $name = "Армянские " . mb_strtolower($type->name) . " " . $city->name_dat . " " . " - " . mb_strtolower($category->name);
                                $description = "Армянские " . mb_strtolower($type->name) . " " . $city->name_dat . " " . " - " . mb_strtolower($category->name);
                                $site_map_type = $this->site_map_types['категория (город)'];
                            }

                            $count = Entity::active()
                                ->where('entity_type_id', $type->id)
                                ->where('city_id', $city->id)
                                ->count();

                            $count = Entity::active()
                                ->where('entity_type_id', $type->id)
                                ->where('city_id', $city->id)
                                ->where(function (Builder $query) use ($category) {
                                    $query
                                        ->where('category_id', $category->id)
                                        ->orWhereHas('fields', function ($que) use ($category) {
                                            $que->where('category_entity.main_category_id', '=', $category->id);
                                        });
                                })->count();

                            SiteMap::updateOrCreate(
                                [
                                    'url' => $url . '/' . $type->transcription . '/' . $category->transcription
                                ],
                                [
                                    'site_map_type_id' => $site_map_type,
                                    'name' => $name,
                                    'title' => $name,
                                    'description' => $description,
                                    'quantity_entity' => $count,
                                    'region_id' => $city->region_id,
                                    'city_id' => $city->id,
                                    'entity_type_id' => $type->id,
                                    'category_id' => $category->id,
                                    'entity_id' => null,
                                    'index' => true
                                ]
                            );
                        }
                    }
                }
            }
        });
    }

    public function pageForEntities()
    {
        Entity::chunk(50, function (Collection $entities) {
            foreach ($entities as $entity) {
                if ($entity->activity) {

                    if ($entity->type->id > 0 && $entity->type->id < 5) {

                        $type = $this->entity_types[$entity->type->id];

                        SiteMap::updateOrCreate(
                            [
                                'url' => 'https://vsearmyane.ru' . '/' . $type . '/' . $entity->id
                            ],
                            [
                                'site_map_type_id' => 10,
                                'name' => $entity->name,
                                'title' => $entity->type->name  . ' - ' . $entity->name,
                                'description' => $entity->description,
                                'quantity_entity' => null,
                                'region_id' => $entity->region_id,
                                'city_id' => $entity->city_id,
                                'entity_type_id' => $entity->entity_type_id,
                                'category_id' => $entity->category_id,
                                'entity_id' => $entity->id,
                                'index' => true
                            ]
                        );

                        SiteMap::updateOrCreate(
                            [
                                'url' => 'https://vsearmyane.ru' . '/' . $type . '/' . $entity->transcription
                            ],
                            [
                                'site_map_type_id' => 10,
                                'name' => $entity->name,
                                'title' => $entity->type->name  . ' - ' . $entity->name,
                                'description' => $entity->description,
                                'quantity_entity' => null,
                                'region_id' => $entity->region_id,
                                'city_id' => $entity->city_id,
                                'entity_type_id' => $entity->entity_type_id,
                                'category_id' => $entity->category_id,
                                'entity_id' => $entity->id,
                                'index' => true
                            ]
                        );
                    }
                }
            }
        });
    }

    public function addFile()
    {
        $path = public_path('sitemap.xml');
        $iteration = 1;
        $sitemapIndex = SitemapIndex::create();

        SiteMap::chunk(10000, function (Collection $sitemaps, $iteration) use ($path, $sitemapIndex) {

            $sitemapIndex->add("sitemap-$iteration.xml")
                ->writeToFile($path);

            $sitemapGenerat = SM::create();

            foreach ($sitemaps as $sitemap) {
                $sitemapGenerat->add($sitemap->url);
            }

            $sitemapGenerat->writeToFile(public_path("sitemap-$iteration.xml"));

            $iteration++;
        });
    }
}
