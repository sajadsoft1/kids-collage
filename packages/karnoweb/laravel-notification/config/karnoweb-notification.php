<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Table prefix for package tables
    |--------------------------------------------------------------------------
    */
    'table_prefix' => 'karnoweb_',

    /*
    |--------------------------------------------------------------------------
    | Channel configuration (defaults + per-event overrides)
    |--------------------------------------------------------------------------
    */
    'channels' => [
        'defaults' => [
            'channels' => [
                'database' => ['enabled' => true, 'togglable' => false],
                'email' => ['enabled' => true, 'togglable' => true],
                'sms' => ['enabled' => false, 'togglable' => true],
            ],
        ],
        'events' => [
            // Example: 'auth_register' => ['channels' => ['email' => ['enabled' => true, 'togglable' => true]]],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Channels that should be queued (dispatched via jobs)
    |--------------------------------------------------------------------------
    */
    'queued_channels' => ['email', 'sms'],

    /*
    |--------------------------------------------------------------------------
    | Job class per channel for queued sending
    |--------------------------------------------------------------------------
    */
    'queue_jobs' => [
        'email' => null, // e.g. \App\Jobs\Notifications\SendEmailNotificationJob::class
        'sms' => null,   // e.g. \App\Jobs\Notifications\SendSmsNotificationJob::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification log model (for dispatch tracking)
    |--------------------------------------------------------------------------
    */
    'log_model' => Karnoweb\LaravelNotification\Models\NotificationLog::class,

    /*
    |--------------------------------------------------------------------------
    | Email channel: mailable class (receives payload array)
    |--------------------------------------------------------------------------
    */
    'mailable' => null, // e.g. \App\Mail\NotificationMail::class,

    /*
    |--------------------------------------------------------------------------
    | Template model (for BaseNotification template resolution)
    |--------------------------------------------------------------------------
    */
    'template_model' => Karnoweb\LaravelNotification\Models\NotificationTemplate::class,
];
