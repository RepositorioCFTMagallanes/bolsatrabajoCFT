<?php

return [

    'default' => env('FILESYSTEM_DISK', 'gcs'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'throw' => true,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => true,
        ],

        /*
        |------------------------------------------------------------------
        | Google Cloud Storage (PRODUCCIÓN)
        |------------------------------------------------------------------
        | IMPORTANTE:
        | visibility en private para evitar problemas con ACL.
        */
        'gcs' => [
            'driver' => 'gcs',
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
            'path_prefix' => null,
            'visibility' => 'private',
            'throw' => true,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
