<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

use League\Flysystem\Filesystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Filesystem\FilesystemAdapter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Driver GCS sin ACL
        Storage::extend('gcs', function ($app, $config) {

            $storageClient = new StorageClient([
                'projectId' => $config['project_id'],
            ]);

            $bucket = $storageClient->bucket($config['bucket']);

            // Adapter simple (sin ACL)
            $adapter = new GoogleCloudStorageAdapter($bucket);

            $filesystem = new Filesystem($adapter);

            $config['visibility'] = 'private';

            return new FilesystemAdapter($filesystem, $adapter, $config);
        });
    }
}
