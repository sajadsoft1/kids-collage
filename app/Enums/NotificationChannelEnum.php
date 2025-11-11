<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationChannelEnum: string
{
    use EnumToArray;

    case SMS = 'sms';
    case EMAIL = 'email';
    case PUSH = 'push';
    case DATABASE = 'database';

    public function title(): string
    {
        return match ($this) {
            self::SMS => 'پیامک',
            self::EMAIL => 'ایمیل',
            self::PUSH => 'پوش نوتیفیکیشن',
            self::DATABASE => 'نوتیفیکیشن داخلی',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SMS => 'o-chat-bubble-left-right',
            self::EMAIL => 'o-envelope',
            self::PUSH => 'o-bell-alert',
            self::DATABASE => 'o-bell',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SMS => 'ارسال پیامک به شماره موبایل',
            self::EMAIL => 'ارسال ایمیل به آدرس پست الکترونیک',
            self::PUSH => 'ارسال نوتیفیکیشن به دستگاه موبایل',
            self::DATABASE => 'ذخیره نوتیفیکیشن در سیستم',
        };
    }
}
