<?php

declare(strict_types=1);

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
    | Editor (TinyMCE) Disk
    |--------------------------------------------------------------------------
    |
    | Disk used for editor image uploads (e.g. TinyMCE). Set EDITOR_DISK in .env
    | to use a different disk (e.g. ftp_editor).
    |
    */
    'editor_disk' => env('EDITOR_DISK', 'tinymce'),

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
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],
        'livewire_temp' => [
            'driver' => 'local',
            'root' => storage_path('app/public/livewire-tmp'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],
        'tinymce' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST', '3117204815.cloudydl.com'),
            'username' => env('FTP_USERNAME', 'pz22410'),
            'password' => env('FTP_PASSWORD'),
            'port' => (int) env('FTP_PORT', 21),
            'root' => (string) (env('FTP_MEDIA_ROOT', 'public_html/lilingo')),
            'url' => rtrim(env('FTP_URL', 'https://dl.lilingo.ir'), '/') . '/lilingo',
            'passive' => true,
            'ssl' => env('FTP_SSL', false),
            'timeout' => (int) env('FTP_TIMEOUT', 30),
            'visibility' => 'public',
            'throw' => false,
        ],
        'MEDIA_DISK' => [
            'driver' => 'local',
            'root' => env('MEDIA_LIBRARY_STORAGE_ROOT_PATH', public_path('media')) ?: public_path('media'),
            'url' => env('APP_URL') ? env('APP_URL') . '/media' : '/media',
            'visibility' => 'public',
        ],
        'ftp_media' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST', '3117204815.cloudydl.com'),
            'username' => env('FTP_USERNAME', 'pz22410'),
            'password' => env('FTP_PASSWORD'),
            'port' => (int) env('FTP_PORT', 21),
            'root' => (string) (env('FTP_MEDIA_ROOT', 'public_html/lilingo/media')),
            'url' => rtrim(env('FTP_URL', 'https://dl.lilingo.ir'), '/') . '/lilingo/media',
            'passive' => true,
            'ssl' => env('FTP_SSL', false),
            'timeout' => (int) env('FTP_TIMEOUT', 30),
            'visibility' => 'public',
            'throw' => false,
        ],
        'ftp_ticket' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST', '3117204815.cloudydl.com'),
            'username' => env('FTP_USERNAME', 'pz22410'),
            'password' => env('FTP_PASSWORD'),
            'port' => (int) env('FTP_PORT', 21),
            'root' => (string) (env('FTP_TICKET_ROOT', 'public_html/lilingo/ticket')),
            'url' => rtrim(env('FTP_URL', 'https://dl.lilingo.ir'), '/') . '/lilingo/ticket',
            'passive' => true,
            'ssl' => env('FTP_SSL', false),
            'timeout' => (int) env('FTP_TIMEOUT', 30),
            'visibility' => 'public',
            'throw' => false,
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
