<?php

declare(strict_types=1);

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;

$projectName = 'کیدز کالج';

return [
    'data' => [
        // Authentication Events
        [
            'event' => NotificationEventEnum::AUTH_REGISTER,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: ثبت‌نام شما تکمیل شد. ورود: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: کد تایید شما {{verification_code}} است. معتبر تا 10 دقیقه.',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: بازیابی رمز عبور. لینک: {{reset_password_url}} (معتبر 60 دقیقه)',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: به خانواده ما خوش آمدید! شروع: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: سفارش #{{order_number}} ثبت شد. مبلغ: {{order_amount}} تومان. پرداخت: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: پرداخت سفارش #{{order_number}} موفق. دسترسی فعال شد. مشاهده: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: سفارش #{{order_number}} لغو شد. در صورت پرداخت، مبلغ بازگردانده می‌شود.',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: پرداخت {{payment_amount}} تومان موفق. تراکنش: {{transaction_id}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: پرداخت ناموفق. لطفاً مجدد تلاش کنید: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: ثبت‌نام در "{{course_title}}" ثبت شد. در حال بررسی.',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: ثبت‌نام در "{{course_title}}" تایید شد. شروع: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: ثبت‌نام در "{{course_title}}" رد شد. برای اطلاعات بیشتر با پشتیبانی تماس بگیرید.',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: یادآوری جلسه "{{course_title}}" - {{session_date}} ساعت {{session_time}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: جلسه "{{course_title}}" شروع شد. ورود: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: جلسه "{{course_title}}" به پایان رسید. جزئیات: {{action_url}}',
                    'cta' => null,
                    'placeholders' => [
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        // General Events
        [
            'event' => NotificationEventEnum::ANNOUNCEMENT,
            'channel' => NotificationChannelEnum::SMS,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: {{announcement_title}}. جزئیات: {{action_url}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: هشدار سیستم - {{alert_message}}',
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
                'fa' => [
                    'subject' => null,
                    'title' => null,
                    'subtitle' => null,
                    'body' => 'کیدز کالج: تولدت مبارک! 🎉 هدیه ویژه: {{birthday_gift}}. دریافت: {{action_url}}',
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
