<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin menu
    |--------------------------------------------------------------------------
    */
    'admin' => [
        [
            'name' => 'ЛК',
            'route' => 'dashboard',
            'routeIs' => 'dashboard',
            'sub' => []
        ],
        [
            'name' => 'Главная',
            'route' => 'admin.dashboard',
            'routeIs' => 'admin.dashboard',
            'sub' => []
        ],
        [
            'name' => 'Диагностика',
            'route' => 'admin.diagnostics',
            'routeIs' => 'admin.diagnostics',
            'sub' => []
        ],
        [
            'name' => 'Пользователи',
            'route' => 'admin.user.index',
            'routeIs' => 'admin.user.*',
            'sub' => []
        ],
        [
            'name' => 'Сущности',
            'route' => '',
            'routeIs' => 'admin.entity.*',
            'sub' => [
                [
                    'name' => 'Сущности',
                    'route' => 'admin.entity.index',
                    'routeIs' => 'admin.entity.*',
                ],
                [
                    'name' => 'Сводка',
                    'route' => 'admin.entity.report',
                    'routeIs' => 'admin.entity.report.*',
                ],
                [
                    'name' => 'Сводка 2',
                    'route' => 'admin.entity.report-two',
                    'routeIs' => 'admin.entity.report-two.*',
                ],
                [
                    'name' => 'Сводка дубли',
                    'route' => 'admin.entity.report-double',
                    'routeIs' => 'admin.entity.report-double.*',
                ],
                [
                    'name' => 'Реестр изображени',
                    'route' => 'admin.image.index',
                    'routeIs' => 'admin.image.*',
                ],
            ]
        ],
        [
            'name' => 'Товары',
            'route' => 'admin.offer.index',
            'routeIs' => 'admin.offer.*',
            'sub' => []
        ],
        [
            'name' => 'Категории',
            'route' => 'admin.category.index',
            'routeIs' => 'admin.category.*',
            'sub' => []
        ],
        [
            'name' => 'Направления',
            'route' => 'admin.category-entity.index',
            'routeIs' => 'admin.category-entity.*',
            'sub' => []
        ],
        /* 
            [
                'name' => 'Импорт',
                'route' => '',
                'sub' => [
                    [
                        'name' => 'церкви',
                        'route' => 'admin.import.church',
                        'routeIs' => 'admin.import.church.*',
                    ],
                    [
                        'name' => 'сущности',
                        'route' => 'admin.import.entity',
                        'routeIs' => 'admin.import.entity.*',
                    ],
                    [
                        'name' => 'адвокаты',
                        'route' => 'admin.import.lawyer',
                        'routeIs' => 'admin.import.lawyer.*',
                    ],
                    [
                        'name' => 'врачи',
                        'route' => 'admin.import.doctor',
                        'routeIs' => 'admin.import.doctor.*',
                    ],
                    [
                        'name' => 'категории',
                        'route' => 'admin.import.category',
                        'routeIs' => 'admin.import.category.*',
                    ],
                ]
            ],
            */
        [
            'name' => 'Телеграм',
            'route' => '',
            'routeIs' => 'admin.telegram_group.*',
            'sub' => [
                [
                    'name' => 'Группы',
                    'route' => 'admin.telegram_group.index',
                    'routeIs' => 'admin.telegram_group.*',
                ],
                [
                    'name' => 'Пользователи',
                    'route' => 'admin.telegram_user.index',
                    'routeIs' => 'admin.telegram_user.*',
                ],
                [
                    'name' => 'Сообшения',
                    'route' => 'admin.telegram_message.index',
                    'routeIs' => 'admin.telegram_message.*',
                ],
            ]
        ],
        [
            'name' => 'Типы',
            'route' => 'admin.type.index',
            'routeIs' => 'admin.type.*',
            'sub' => []
        ],
        [
            'name' => 'Сообщения',
            'route' => 'admin.appeal.index',
            'routeIs' => 'admin.appeal.*',
            'sub' => [],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Main menu
    |--------------------------------------------------------------------------
    */
    'main' => [
        'companies'     => [
            'title'         => 'Бизнес справочник',
            'routes'        => [
                'index'         => 'companies.index',
                'region'        => 'companies.region'
            ],
        ],
        'groups'        => [
            'title'         => 'Кружки и сообщества',
            'routes'        => [
                'index'         => 'groups.index',
                'region'        => 'groups.region'
            ]
        ],
        'places'        => [
            'title'         => 'Интересные места',
            'routes'        => [
                'index'         => 'places.index',
                'region'        => 'places.region'
            ]
        ],
        'communities'   => [
            'title'         => 'Общины',
            'routes'        => [
                'index'         => 'communities.index',
                'region'        => 'communities.region'
            ]
        ],
        // 'projects'      => [
        //     'title'         => 'Проекты помощи',
        //     'routes'        => [
        //         'index'         => 'projects.index',
        //         'region'        => 'projects.region'
        //     ]
        // ]
    ]
];
