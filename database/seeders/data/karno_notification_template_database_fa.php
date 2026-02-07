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
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'ثبت‌نام موفق',
                    'subtitle' => 'ثبت‌نام شما با موفقیت انجام شد',
                    'body' => 'سلام {{user_name}} عزیز، به خانواده کیدز کالج خوش آمدید! ثبت‌نام شما با موفقیت انجام شد.',
                    'cta' => [
                        'label' => 'ورود به حساب کاربری',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_CONFIRM,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'تایید حساب کاربری',
                    'subtitle' => 'کد تایید را وارد کنید تا حساب شما فعال شود',
                    'body' => 'سلام {{user_name}} عزیز، کد تایید شما {{verification_code}} است. این کد تا 10 دقیقه معتبر است.',
                    'cta' => [
                        'label' => 'تایید حساب کاربری',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'verification_code',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_FORGOT_PASSWORD,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'بازیابی رمز عبور',
                    'subtitle' => 'درخواست بازیابی رمز عبور شما',
                    'body' => 'سلام {{user_name}} عزیز، درخواست بازیابی رمز عبور برای حساب کاربری شما ثبت شده است. این لینک تا 60 دقیقه معتبر است.',
                    'cta' => [
                        'label' => 'بازیابی رمز عبور',
                        'url' => '{{reset_password_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'reset_password_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_WELCOME,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'خوش آمدید',
                    'subtitle' => 'به خانواده کیدز کالج خوش آمدید',
                    'body' => 'سلام {{user_name}} عزیز، به خانواده کیدز کالج خوش آمدید! اکنون می‌توانید از تمام امکانات پلتفرم استفاده کنید.',
                    'cta' => [
                        'label' => 'شروع کنید',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Order Events
        [
            'event' => NotificationEventEnum::ORDER_CREATED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'سفارش جدید',
                    'subtitle' => 'سفارش شما با موفقیت ثبت شد',
                    'body' => 'سلام {{user_name}} عزیز، سفارش شما با شماره {{order_number}} با موفقیت ثبت شد. مبلغ کل: {{order_amount}} تومان. برای تکمیل سفارش و پرداخت اقدام کنید.',
                    'cta' => [
                        'label' => 'پرداخت سفارش',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'order_number',
                        'order_amount',
                        'order_date',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ORDER_PAID,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'پرداخت موفق',
                    'subtitle' => 'پرداخت سفارش شما با موفقیت انجام شد',
                    'body' => 'سلام {{user_name}} عزیز، پرداخت سفارش شما با شماره {{order_number}} با موفقیت انجام شد. دسترسی شما به دوره‌های خریداری شده فعال شده است.',
                    'cta' => [
                        'label' => 'مشاهده دوره‌ها',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'order_number',
                        'payment_amount',
                        'transaction_id',
                        'payment_date',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ORDER_CANCELLED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'لغو سفارش',
                    'subtitle' => 'سفارش شما لغو شد',
                    'body' => 'سلام {{user_name}} عزیز، متأسفانه سفارش شما با شماره {{order_number}} لغو شد. در صورت پرداخت، مبلغ به حساب شما بازگردانده خواهد شد.',
                    'cta' => [
                        'label' => 'مشاهده جزئیات',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'order_number',
                        'cancellation_reason',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Payment Events
        [
            'event' => NotificationEventEnum::PAYMENT_SUCCESS,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'پرداخت موفق',
                    'subtitle' => 'پرداخت شما با موفقیت انجام شد',
                    'body' => 'سلام {{user_name}} عزیز، پرداخت شما با مبلغ {{payment_amount}} تومان با موفقیت انجام شد. شماره تراکنش: {{transaction_id}}',
                    'cta' => [
                        'label' => 'مشاهده محتوا',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'payment_amount',
                        'transaction_id',
                        'payment_date',
                        'payment_method',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::PAYMENT_FAILED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'پرداخت ناموفق',
                    'subtitle' => 'پرداخت شما انجام نشد',
                    'body' => 'سلام {{user_name}} عزیز، متأسفانه پرداخت شما انجام نشد. لطفاً مجدداً تلاش کنید یا در صورت بروز مشکل، با پشتیبانی ما در تماس باشید.',
                    'cta' => [
                        'label' => 'تلاش مجدد',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'failure_reason',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Enrollment Events
        [
            'event' => NotificationEventEnum::ENROLLMENT_CREATED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'ثبت‌نام در دوره',
                    'subtitle' => 'درخواست ثبت‌نام شما ثبت شد',
                    'body' => 'سلام {{user_name}} عزیز، درخواست ثبت‌نام شما در دوره "{{course_title}}" ثبت شد. این درخواست در حال بررسی است.',
                    'cta' => [
                        'label' => 'مشاهده وضعیت',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ENROLLMENT_APPROVED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'تایید ثبت‌نام',
                    'subtitle' => 'ثبت‌نام شما در دوره تایید شد',
                    'body' => 'سلام {{user_name}} عزیز، خبر خوب! ثبت‌نام شما در دوره "{{course_title}}" تایید شد. اکنون می‌توانید به تمام محتوای دوره دسترسی داشته باشید.',
                    'cta' => [
                        'label' => 'شروع دوره',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ENROLLMENT_REJECTED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'رد ثبت‌نام',
                    'subtitle' => 'ثبت‌نام شما در دوره رد شد',
                    'body' => 'سلام {{user_name}} عزیز، متأسفانه ثبت‌نام شما در دوره "{{course_title}}" رد شد. در صورت نیاز به اطلاعات بیشتر، با پشتیبانی ما در تماس باشید.',
                    'cta' => [
                        'label' => 'مشاهده دوره‌ها',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'rejection_reason',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Course Events
        [
            'event' => NotificationEventEnum::COURSE_SESSION_REMINDER,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'یادآوری جلسه',
                    'subtitle' => 'یادآوری جلسه پیش رو',
                    'body' => 'سلام {{user_name}} عزیز، این یک یادآوری است که جلسه دوره "{{course_title}}" به زودی شروع می‌شود. تاریخ: {{session_date}} - ساعت: {{session_time}}',
                    'cta' => [
                        'label' => 'ورود به جلسه',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'session_date',
                        'session_time',
                        'session_duration',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::COURSE_SESSION_STARTED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'شروع جلسه',
                    'subtitle' => 'جلسه دوره آغاز شده است',
                    'body' => 'سلام {{user_name}} عزیز، جلسه دوره "{{course_title}}" اکنون آغاز شده است. برای حضور در جلسه اقدام کنید.',
                    'cta' => [
                        'label' => 'ورود به جلسه',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::COURSE_SESSION_ENDED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'پایان جلسه',
                    'subtitle' => 'جلسه دوره به پایان رسید',
                    'body' => 'سلام {{user_name}} عزیز، جلسه دوره "{{course_title}}" به پایان رسید. می‌توانید جزئیات، فیلم ضبط شده و مطالب تکمیلی را مشاهده کنید.',
                    'cta' => [
                        'label' => 'مشاهده جزئیات',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        // General Events
        [
            'event' => NotificationEventEnum::ANNOUNCEMENT,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'اطلاعیه جدید',
                    'subtitle' => '{{announcement_title}}',
                    'body' => 'سلام {{user_name}} عزیز، اطلاعیه جدید: {{announcement_title}}',
                    'cta' => [
                        'label' => 'مشاهده اطلاعیه',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'announcement_title',
                        'announcement_body',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::SYSTEM_ALERT,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'هشدار سیستم',
                    'subtitle' => '{{alert_title}}',
                    'body' => 'سلام {{user_name}} عزیز، هشدار سیستم: {{alert_message}}',
                    'cta' => [
                        'label' => 'مشاهده جزئیات',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'alert_title',
                        'alert_message',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::BIRTHDAY_REMINDER,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'fa' => [
                    'subject' => null,
                    'title' => 'تولدت مبارک!',
                    'subtitle' => '{{user_name}} عزیز، تولدت مبارک!',
                    'body' => 'سلام {{user_name}} عزیز، تولدت مبارک! 🎉 امیدواریم سال جدید پر از موفقیت، شادی و یادگیری برای تو باشد.',
                    'cta' => [
                        'label' => 'دریافت هدیه',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'birthday_gift',
                        'action_url',
                    ],
                ],
            ],
        ],
    ],
];
