<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use Google\Cloud\Storage\StorageClient;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('gcs', function ($app, $config) {

            // 1) Cliente GCS
            $storageClient = new StorageClient([
                'projectId' => $config['project_id'] ?? null,
            ]);

            // 2) Bucket
            $bucketName = $config['bucket'] ?? null;

            if (empty($bucketName)) {
                throw new \InvalidArgumentException(
                    'GCS bucket no configurado en filesystems.php (disks.gcs.bucket).'
                );
            }

            $bucket = $storageClient->bucket($bucketName);

            // 3) Prefijo opcional
            $pathPrefix = $config['path_prefix'] ?? '';

            if ($pathPrefix === null) {
                $pathPrefix = '';
            }

            // 4) Adapter correcto (versión que tienes instalada)
            $adapter = new GoogleCloudStorageAdapter(
                $bucket,
                $pathPrefix
            );

            // 5) Filesystem
            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter(
                $filesystem,
                $adapter,
                $config
            );
        });
    }
}
