<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification;

use Karnoweb\LaravelNotification\Contracts\NotificationChannel;

enum NotificationChannelEnum: string implements NotificationChannel
{
    case SMS = 'sms';
    case EMAIL = 'email';
    case DATABASE = 'database';
    case FIREBASE = 'firebase';
    case TELEGRAM = 'telegram';
    case WHATSAPP = 'whatsapp';
    case PUSH = 'push';

    /** Required by NotificationChannel interface (backed enum). */
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

    public function title(): string
    {
        return match ($this) {
            self::SMS => 'SMS',
            self::EMAIL => 'Email',
            self::DATABASE => 'Database',
            self::FIREBASE, self::PUSH => 'Push',
            self::TELEGRAM => 'Telegram',
            self::WHATSAPP => 'WhatsApp',
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
            self::SMS => 'Send SMS to mobile number',
            self::EMAIL => 'Send email to email address',
            self::DATABASE => 'Store notification in system',
            self::FIREBASE, self::PUSH => 'Push notification',
            self::TELEGRAM => 'Send via Telegram bot',
            self::WHATSAPP => 'Send via WhatsApp',
        };
    }

    public function isFutureChannel(): bool
    {
        return match ($this) {
            self::FIREBASE, self::TELEGRAM, self::WHATSAPP, self::PUSH => true,
            default => false,
        };
    }
}
