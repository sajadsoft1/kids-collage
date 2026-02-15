<?php

declare(strict_types=1);

return [
    'table_prefix' => 'karnoweb_',

    'channels' => [
        'defaults' => [
            'channels' => [
                'database' => ['enabled' => true, 'togglable' => false],
                'email' => ['enabled' => true, 'togglable' => true],
                'sms' => ['enabled' => false, 'togglable' => true],
            ],
        ],
        'events' => [
            'auth_register' => [
                'channels' => [
                    'email' => ['enabled' => true, 'togglable' => true],
                    'sms' => ['enabled' => true, 'togglable' => true],
                ],
            ],
        ],
    ],

    'queued_channels' => ['email', 'sms'],

    'queue_jobs' => [
        'email' => \App\Jobs\Notifications\SendEmailNotificationJob::class,
        'sms' => \App\Jobs\Notifications\SendSmsNotificationJob::class,
    ],

    'log_model' => \App\Models\NotificationLog::class,

    'mailable' => \App\Mail\NotificationMail::class,
];
