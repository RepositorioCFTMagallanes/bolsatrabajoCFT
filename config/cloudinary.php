<?php

/*
 * This file is part of the Laravel Cloudinary package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL (Prioridad 1)
    |--------------------------------------------------------------------------
    |
    | Esta es la variable principal que estás usando en cloudbuild.yaml.
    | La librería intentará usar esta URL primero para configurarse.
    |
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration Array (OBLIGATORIO)
    |--------------------------------------------------------------------------
    |
    | IMPORTANTE: Aunque uses 'cloud_url', la librería requiere que este array 
    | exista para evitar el error "Undefined array key 'cloud'".
    |
    | Si tu CLOUDINARY_URL falla o no se detecta, usará estos valores.
    |
    */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification URL
    |--------------------------------------------------------------------------
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload Preset
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Router / Controller Configuration
    |--------------------------------------------------------------------------
    */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),

];