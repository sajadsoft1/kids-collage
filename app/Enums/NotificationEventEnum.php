<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationEventEnum: string
{
    use EnumToArray;

    // Authentication Events
    case AUTH_REGISTER        = 'auth_register';
    case AUTH_CONFIRM         = 'auth_confirm';
    case AUTH_FORGOT_PASSWORD = 'auth_forgot_password';
    case AUTH_WELCOME         = 'auth_welcome';

    // Order Events
    case ORDER_CREATED   = 'order_created';
    case ORDER_PAID      = 'order_paid';
    case ORDER_CANCELLED = 'order_cancelled';

    // Payment Events
    case PAYMENT_SUCCESS = 'payment_success';
    case PAYMENT_FAILED  = 'payment_failed';

    // Enrollment Events
    case ENROLLMENT_CREATED  = 'enrollment_created';
    case ENROLLMENT_APPROVED = 'enrollment_approved';
    case ENROLLMENT_REJECTED = 'enrollment_rejected';

    // Course Events
    case COURSE_SESSION_REMINDER = 'course_session_reminder';
    case COURSE_SESSION_STARTED  = 'course_session_started';
    case COURSE_SESSION_ENDED    = 'course_session_ended';

    // Ticket Events
    case TICKET_CREATED  = 'ticket_created';
    case TICKET_REPLIED  = 'ticket_replied';
    case TICKET_RESOLVED = 'ticket_resolved';

    // General Events
    case ANNOUNCEMENT      = 'announcement';
    case SYSTEM_ALERT      = 'system_alert';
    case BIRTHDAY_REMINDER = 'birthday_reminder';

    public function title(): string
    {
        return match ($this) {
            self::AUTH_REGISTER => 'ثبت نام کاربر',
            self::AUTH_CONFIRM => 'تایید ثبت نام',
            self::AUTH_FORGOT_PASSWORD => 'بازیابی رمز عبور',
            self::AUTH_WELCOME => 'خوش آمدگویی',
            self::ORDER_CREATED => 'ایجاد سفارش',
            self::ORDER_PAID => 'پرداخت سفارش',
            self::ORDER_CANCELLED => 'لغو سفارش',
            self::PAYMENT_SUCCESS => 'پرداخت موفق',
            self::PAYMENT_FAILED => 'پرداخت ناموفق',
            self::ENROLLMENT_CREATED => 'ثبت نام در دوره',
            self::ENROLLMENT_APPROVED => 'تایید ثبت نام',
            self::ENROLLMENT_REJECTED => 'رد ثبت نام',
            self::COURSE_SESSION_REMINDER => 'یادآوری جلسه',
            self::COURSE_SESSION_STARTED => 'شروع جلسه',
            self::COURSE_SESSION_ENDED => 'پایان جلسه',
            self::TICKET_CREATED => 'ایجاد تیکت',
            self::TICKET_REPLIED => 'پاسخ به تیکت',
            self::TICKET_RESOLVED => 'حل تیکت',
            self::ANNOUNCEMENT => 'اطلاعیه عمومی',
            self::SYSTEM_ALERT => 'هشدار سیستم',
            self::BIRTHDAY_REMINDER => 'یادآوری تولد',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::AUTH_REGISTER => 'اطلاع‌رسانی هنگام ثبت نام کاربر جدید',
            self::AUTH_CONFIRM => 'ارسال کد تایید برای فعال‌سازی حساب',
            self::AUTH_FORGOT_PASSWORD => 'ارسال لینک بازیابی رمز عبور',
            self::AUTH_WELCOME => 'پیام خوش آمدگویی بعد از ثبت نام',
            self::ORDER_CREATED => 'اطلاع‌رسانی هنگام ایجاد سفارش جدید',
            self::ORDER_PAID => 'تایید پرداخت موفق سفارش',
            self::ORDER_CANCELLED => 'اطلاع‌رسانی لغو سفارش',
            self::PAYMENT_SUCCESS => 'تایید پرداخت موفق',
            self::PAYMENT_FAILED => 'اطلاع‌رسانی پرداخت ناموفق',
            self::ENROLLMENT_CREATED => 'اطلاع‌رسانی ثبت نام در دوره',
            self::ENROLLMENT_APPROVED => 'تایید ثبت نام در دوره',
            self::ENROLLMENT_REJECTED => 'اطلاع‌رسانی رد ثبت نام',
            self::COURSE_SESSION_REMINDER => 'یادآوری جلسه قبل از شروع',
            self::COURSE_SESSION_STARTED => 'اطلاع‌رسانی شروع جلسه',
            self::COURSE_SESSION_ENDED => 'اطلاع‌رسانی پایان جلسه',
            self::TICKET_CREATED => 'تایید ایجاد تیکت جدید',
            self::TICKET_REPLIED => 'اطلاع‌رسانی پاسخ جدید به تیکت',
            self::TICKET_RESOLVED => 'اطلاع‌رسانی حل تیکت',
            self::ANNOUNCEMENT => 'اطلاعیه‌های عمومی سیستم',
            self::SYSTEM_ALERT => 'هشدارهای مهم سیستم',
            self::BIRTHDAY_REMINDER => 'تبریک تولد',
        };
    }

    public function category(): string
    {
        return match ($this) {
            self::AUTH_REGISTER,
            self::AUTH_CONFIRM,
            self::AUTH_FORGOT_PASSWORD,
            self::AUTH_WELCOME => 'احراز هویت',

            self::ORDER_CREATED,
            self::ORDER_PAID,
            self::ORDER_CANCELLED => 'سفارشات',

            self::PAYMENT_SUCCESS,
            self::PAYMENT_FAILED => 'پرداخت‌ها',

            self::ENROLLMENT_CREATED,
            self::ENROLLMENT_APPROVED,
            self::ENROLLMENT_REJECTED => 'ثبت‌نام دوره‌ها',

            self::COURSE_SESSION_REMINDER,
            self::COURSE_SESSION_STARTED,
            self::COURSE_SESSION_ENDED => 'جلسات دوره',

            self::TICKET_CREATED,
            self::TICKET_REPLIED,
            self::TICKET_RESOLVED => 'پشتیبانی',

            self::ANNOUNCEMENT,
            self::SYSTEM_ALERT,
            self::BIRTHDAY_REMINDER => 'عمومی',
        };
    }

    public static function groupedByCategory(): array
    {
        $grouped = [];
        foreach (self::cases() as $case) {
            $category = $case->category();
            if ( ! isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $case;
        }

        return $grouped;
    }
}
