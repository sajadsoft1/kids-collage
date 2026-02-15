<?php

declare(strict_types=1);

namespace App\Enums;

use Karnoweb\LaravelNotification\Contracts\NotificationChannel;

enum NotificationChannelEnum: string implements NotificationChannel
{
    use EnumToArray;

    case SMS = 'sms';
    case EMAIL = 'email';
    case DATABASE = 'database';
    case FIREBASE = 'firebase';
    case TELEGRAM = 'telegram';
    case WHATSAPP = 'whatsapp';
    case PUSH = 'push';

    public function title(): string
    {
        return match ($this) {
            self::SMS => 'پیامک',
            self::EMAIL => 'ایمیل',
            self::DATABASE => 'نوتیفیکیشن داخلی',
            self::FIREBASE, self::PUSH => 'پوش نوتیفیکیشن',
            self::TELEGRAM => 'تلگرام',
            self::WHATSAPP => 'واتس‌اپ',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SMS => 'o-chat-bubble-left-right',
            self::EMAIL => 'o-envelope',
            self::DATABASE => 'o-bell',
            self::FIREBASE, self::PUSH => 'o-bell-alert',
            self::TELEGRAM => 'o-paper-airplane',
            self::WHATSAPP => 'o-chat-bubble-left-ellipsis',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SMS => 'ارسال پیامک به شماره موبایل',
            self::EMAIL => 'ارسال ایمیل به آدرس پست الکترونیک',
            self::DATABASE => 'ذخیره نوتیفیکیشن در سیستم',
            self::FIREBASE, self::PUSH => 'ارسال اعلان از طریق سرویس پوش (Firebase)',
            self::TELEGRAM => 'ارسال پیام از طریق ربات تلگرام',
            self::WHATSAPP => 'ارسال پیام از طریق واتس‌اپ',
        };
    }

    public function isFutureChannel(): bool
    {
        return match ($this) {
            self::FIREBASE, self::TELEGRAM, self::WHATSAPP, self::PUSH => true,
            default => false,
        };
    }

    /** Required by NotificationChannel interface (backed enum value). */
    public function value(): string
    {
        return $this->value;
    }

    public function driverBinding(): string
    {
        return match ($this) {
            self::SMS => 'karnoweb.notifications.channels.sms',
            self::EMAIL => 'karnoweb.notifications.channels.email',
            self::DATABASE => 'karnoweb.notifications.channels.database',
            self::FIREBASE, self::PUSH => 'karnoweb.notifications.channels.firebase',
            self::TELEGRAM => 'karnoweb.notifications.channels.telegram',
            self::WHATSAPP => 'karnoweb.notifications.channels.whatsapp',
        };
    }
}
