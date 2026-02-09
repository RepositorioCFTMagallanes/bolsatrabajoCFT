<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Disk por defecto del sistema.
    | En producción debe ser "gcs".
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
            'throw' => true,
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
            'throw' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Google Cloud Storage (PRODUCCIÓN)
        |--------------------------------------------------------------------------
        */
        'gcs' => [
            'driver' => 'gcs',

            // ID del proyecto
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),

            // Nombre del bucket
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET'),

            // Prefijo opcional (puede quedar null)
            'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', null),

            // Visibilidad pública
            'visibility' => 'public',

            // URL base del bucket (CRÍTICO para mostrar imágenes)
            'url' => env('GOOGLE_CLOUD_STORAGE_URL'),

            // En Cloud Run se usa la service account automáticamente
            'key_file' => null,

            // MUY IMPORTANTE: lanzar errores reales
            'throw' => true,
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
            'throw' => true,
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
