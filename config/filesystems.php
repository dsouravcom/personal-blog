<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        // ─── Cloudflare R2 ────────────────────────────────────────────────────
        // R2 is S3-compatible, so we use the 's3' driver but point the endpoint
        // at your R2 account: https://<ACCOUNT_ID>.r2.cloudflarestorage.com
        // Files are stored privately; we serve them via the R2 public bucket URL
        // (or a custom domain) stored in R2_PUBLIC_URL.
        // ─── Cloudflare R2 ────────────────────────────────────────────────────
        // R2 is S3-compatible. We point the AWS S3 driver at R2's endpoint.
        //
        // SSL note: on Linux/production the system CA bundle is used automatically.
        // On Windows dev PHP ships without a CA bundle, so we explicitly pass
        // the cacert.pem path via the Guzzle 'http' option — this works at the
        // SDK level regardless of whether the php.ini was reloaded.
        //
        // R2_SSL_CERT: set in .env on Windows, leave empty on Linux.
        'r2' => [
            'driver'   => 's3',
            'key'      => env('R2_ACCESS_KEY_ID'),
            'secret'   => env('R2_SECRET_ACCESS_KEY'),
            'region'   => 'auto',          // R2 always uses 'auto'
            'bucket'   => env('R2_BUCKET'),
            'endpoint' => env('R2_ENDPOINT'),   // https://<account_id>.r2.cloudflarestorage.com
            'use_path_style_endpoint' => true,  // required for R2
            'url'      => env('R2_PUBLIC_URL'),  // public URL served to browsers
            'throw'    => true,
            'report'   => false,
            // SSL verification for HTTPS requests to R2.
            // - R2_SSL_CERT set to a real file path  → use that CA bundle (Windows dev)
            // - R2_SSL_CERT empty or path doesn't exist → use OS system certs (Linux/Railway)
            // file_exists() check means you can safely copy your Windows .env to a server
            // without breaking anything — the wrong path is just ignored.
            'http' => [
                'verify' => (env('R2_SSL_CERT') && file_exists(env('R2_SSL_CERT')))
                    ? env('R2_SSL_CERT')  // Windows dev: use the downloaded cacert.pem
                    : true,              // Linux/Railway: use OS system CA store
            ],
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
