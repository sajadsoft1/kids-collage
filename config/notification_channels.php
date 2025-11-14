<?php

declare(strict_types=1);

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Notification Channel Configuration
    |--------------------------------------------------------------------------
    |
    | این بخش مشخص می‌کند که در حالت کلی برای هر ایونت از چه کانال‌هایی
    | استفاده می‌شود، وضعیت اولیه آن‌ها چیست و آیا کاربر اجازه تغییر دارد یا خیر.
    |
    */
    'defaults' => [
        'queue' => 'notifications',
        'channels' => [
            NotificationChannelEnum::DATABASE->value => [
                'enabled' => true,
                'togglable' => false,
            ],
            NotificationChannelEnum::EMAIL->value => [
                'enabled' => true,
                'togglable' => true,
            ],
            NotificationChannelEnum::SMS->value => [
                'enabled' => false,
                'togglable' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Specific Overrides
    |--------------------------------------------------------------------------
    |
    | اگر ایونتی نیاز به کانال‌ها یا مقادیر متفاوت داشته باشد، در اینجا تعریف کنید.
    | مثال: COURSE_SESSION_REMINDER فقط از دیتابیس و پیامک استفاده می‌کند.
    |
    */
    'events' => [
        NotificationEventEnum::COURSE_SESSION_REMINDER->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::SMS->value => [
                    'enabled' => true,
                    'togglable' => true,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => false,
                    'togglable' => false,
                ],
            ],
        ],

        NotificationEventEnum::COURSE_SESSION_STARTED->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => true,
                    'togglable' => true,
                ],
            ],
        ],

        NotificationEventEnum::COURSE_SESSION_ENDED->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => true,
                    'togglable' => true,
                ],
            ],
        ],

        NotificationEventEnum::ANNOUNCEMENT->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => true,
                    'togglable' => true,
                ],
            ],
        ],

        NotificationEventEnum::SYSTEM_ALERT->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::SMS->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
            ],
        ],

        NotificationEventEnum::TICKET_CREATED->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => true,
                    'togglable' => true,
                ],
                NotificationChannelEnum::SMS->value => [
                    'enabled' => false,
                    'togglable' => true,
                ],
            ],
        ],

        NotificationEventEnum::TICKET_REPLIED->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => true,
                    'togglable' => true,
                ],
                NotificationChannelEnum::SMS->value => [
                    'enabled' => false,
                    'togglable' => true,
                ],
            ],
        ],

        NotificationEventEnum::TICKET_RESOLVED->value => [
            'channels' => [
                NotificationChannelEnum::DATABASE->value => [
                    'enabled' => true,
                    'togglable' => false,
                ],
                NotificationChannelEnum::EMAIL->value => [
                    'enabled' => true,
                    'togglable' => true,
                ],
                NotificationChannelEnum::SMS->value => [
                    'enabled' => false,
                    'togglable' => true,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Future Channels Scaffold
    |--------------------------------------------------------------------------
    |
    | این آرایه فقط به ما کمک می‌کند تا بدانیم برای چه کانال‌هایی هنوز
    | درایور پیاده‌سازی نشده ولی ساختار آن‌ها باید در سیستم لحاظ شود.
    |
    */
    'future_channels' => [
        NotificationChannelEnum::FIREBASE->value,
        NotificationChannelEnum::TELEGRAM->value,
        NotificationChannelEnum::WHATSAPP->value,
        NotificationChannelEnum::PUSH->value,
    ],
];
