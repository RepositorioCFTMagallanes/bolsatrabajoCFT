<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Disco por defecto del sistema.
    | Debe ser "local" para evitar errores internos y CSS roto.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Configuración de discos de almacenamiento.
    |
    */

    'disks' => [

        /*
        |--------------------------------------------------------------------------
        | Local (privado)
        |--------------------------------------------------------------------------
        */
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
        ],

        /*
        |--------------------------------------------------------------------------
        | Public (solo desarrollo)
        |--------------------------------------------------------------------------
        */
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        /*
        |--------------------------------------------------------------------------
        | Google Cloud Storage (producción)
        |--------------------------------------------------------------------------
        */
        'gcs' => [
            'driver' => 'gcs',
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
            'path_prefix' => null,
            'visibility' => 'public',
        ],

        /*
        |--------------------------------------------------------------------------
        | S3 (no usado actualmente)
        |--------------------------------------------------------------------------
        */
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
