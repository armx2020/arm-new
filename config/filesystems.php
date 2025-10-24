<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('S3_ACCESS_KEY') ?: env('AWS_ACCESS_KEY_ID'),
            'secret' => env('S3_SECRET_KEY') ?: env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'ru-1'),
            'bucket' => env('AWS_BUCKET', '46885a37-67c8e067-4002-4498-a06b-cb98be807ea3'),
            'root' => 'storage/app/public',
            'url' => env('AWS_URL', 'https://s3.timeweb.cloud/46885a37-67c8e067-4002-4498-a06b-cb98be807ea3'),
            'endpoint' => env('AWS_ENDPOINT', 'https://s3.timeweb.cloud'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
            'throw' => true,
            'visibility' => 'public',
        ],

        'production' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('PRODUCTION_STORAGE_URL', 'https://vsearmyne.ru/storage'),
            'visibility' => 'public',
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
