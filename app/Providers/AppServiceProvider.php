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
        // Si ya lo tienes, déjalo tal cual:
        // \Illuminate\Support\Facades\URL::forceScheme('https');

        Storage::extend('gcs', function ($app, $config) {
            // 1) Cliente GCS (en Cloud Run normalmente basta con ADC por service account del servicio)
            $storageClient = new StorageClient([
                'projectId' => $config['project_id'] ?? null,
                // Si alguna vez usas key file, podrías agregar:
                // 'keyFilePath' => $config['key_file'] ?? null,
            ]);

            // 2) Bucket
            $bucketName = $config['bucket'] ?? null;
            if (!$bucketName) {
                throw new \InvalidArgumentException('GCS bucket no configurado (filesystems.php -> disks.gcs.bucket).');
            }

            $bucket = $storageClient->bucket($bucketName);

            // 3) Adapter Flysystem v3
            $pathPrefix = $config['path_prefix'] ?? null; // puede ser null
            $adapter = new GoogleCloudStorageAdapter($bucket, $pathPrefix);

            // 4) Filesystem + FilesystemAdapter de Laravel
            $filesystem = new Filesystem($adapter);

            return new FilesystemAdapter(
                $filesystem,
                $adapter,
                $config
            );
        });
    }
}
