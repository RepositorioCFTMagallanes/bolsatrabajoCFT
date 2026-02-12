<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Illuminate\Support\Facades\URL::forceScheme('https');

        Storage::extend('gcs', function ($app, $config) {

            $storageClient = new StorageClient([
                'projectId' => $config['project_id'] ?? null,
            ]);

            $bucketName = $config['bucket'] ?? null;

            if (empty($bucketName)) {
                throw new \InvalidArgumentException(
                    'GCS bucket no configurado en filesystems.php (disks.gcs.bucket).'
                );
            }

            $bucket = $storageClient->bucket($bucketName);

            $pathPrefix = $config['path_prefix'] ?? '';

            if ($pathPrefix === null) {
                $pathPrefix = '';
            }

            $adapter = new GoogleCloudStorageAdapter(
                $bucket,
                $pathPrefix
            );

            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter(
                $filesystem,
                $adapter,
                $config
            );
        });
    }
}
