<?php

/*
|--------------------------------------------------------------------------
| Cloudinary Configuration
|--------------------------------------------------------------------------
|
| Configuración mínima y estable para el paquete cloudinary-laravel.
| Compatible con Laravel 12 y Cloud Run.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Cloud credentials (OBLIGATORIO)
    |--------------------------------------------------------------------------
    |
    | Estas variables deben existir en el entorno de Cloud Run.
    |
    */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL (opcional pero recomendado)
    |--------------------------------------------------------------------------
    |
    | Formato:
    | cloudinary://API_KEY:API_SECRET@CLOUD_NAME
    |
    */
    'url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */
    'secure' => true,

];
