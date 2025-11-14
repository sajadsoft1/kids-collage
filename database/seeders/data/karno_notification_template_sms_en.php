<?php

declare(strict_types=1);

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;

$projectName = 'Kids Collage';

return [
    'data' => [
        // Authentication Events
        [
            'event' => NotificationEventEnum::AUTH_REGISTER,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Registration complete. Login: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_CONFIRM,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Verification code {{verification_code}}. Valid 10 min.',
                    'cta' => null,
                    'placeholders' => [
                        'verification_code',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_FORGOT_PASSWORD,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Password reset. Link: {{reset_password_url}} (Valid 60 min)',
                    'cta' => null,
                    'placeholders' => [
                        'reset_password_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_WELCOME,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Welcome! Start: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'action_url',
                    ],
                ],
            ],
        ],
        // Order Events
        [
            'event' => NotificationEventEnum::ORDER_CREATED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Order #{{order_number}} created. Amount: ${{order_amount}}. Pay: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'order_number',
                        'order_amount',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ORDER_PAID,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Order #{{order_number}} paid. Access activated. View: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'order_number',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ORDER_CANCELLED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Order #{{order_number}} cancelled. Refund will be processed if paid.',
                    'cta' => null,
                    'placeholders' => [
                        'order_number',
                    ],
                ],
            ],
        ],
        // Payment Events
        [
            'event' => NotificationEventEnum::PAYMENT_SUCCESS,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Payment ${{payment_amount}} successful. Transaction: {{transaction_id}}',
                    'cta' => null,
                    'placeholders' => [
                        'payment_amount',
                        'transaction_id',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::PAYMENT_FAILED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Payment failed. Please try again: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'action_url',
                    ],
                ],
            ],
        ],
        // Enrollment Events
        [
            'event' => NotificationEventEnum::ENROLLMENT_CREATED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Enrollment in "{{course_title}}" submitted. Under review.',
                    'cta' => null,
                    'placeholders' => [
                        'course_title',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ENROLLMENT_APPROVED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Enrollment in "{{course_title}}" approved. Start: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ENROLLMENT_REJECTED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Enrollment in "{{course_title}}" rejected. Contact support for info.',
                    'cta' => null,
                    'placeholders' => [
                        'course_title',
                    ],
                ],
            ],
        ],
        // Course Events
        [
            'event' => NotificationEventEnum::COURSE_SESSION_REMINDER,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Reminder - "{{course_title}}" session on {{session_date}} at {{session_time}}',
                    'cta' => null,
                    'placeholders' => [
                        'course_title',
                        'session_date',
                        'session_time',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::COURSE_SESSION_STARTED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: "{{course_title}}" session started. Join: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::COURSE_SESSION_ENDED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: "{{course_title}}" session ended. Details: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Ticket Events
        [
            'event' => NotificationEventEnum::TICKET_CREATED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Ticket #{{ticket_number}} created. Subject: {{ticket_subject}}',
                    'cta' => null,
                    'placeholders' => [
                        'ticket_number',
                        'ticket_subject',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::TICKET_REPLIED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: New reply to ticket #{{ticket_number}}. View: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'ticket_number',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::TICKET_RESOLVED,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Ticket #{{ticket_number}} resolved.',
                    'cta' => null,
                    'placeholders' => [
                        'ticket_number',
                    ],
                ],
            ],
        ],
        // General Events
        [
            'event' => NotificationEventEnum::ANNOUNCEMENT,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: {{announcement_title}}. Details: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'announcement_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::SYSTEM_ALERT,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: System alert - {{alert_message}}',
                    'cta' => null,
                    'placeholders' => [
                        'alert_message',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::BIRTHDAY_REMINDER,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'Kids Collage: Happy Birthday! 🎉 Special gift: {{birthday_gift}}. Claim: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'birthday_gift',
                        'action_url',
                    ],
                ],
            ],
        ],
    ],
];
