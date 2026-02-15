<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Contracts;

/**
 * Represents a notification channel (e.g. sms, email, database).
 * App may use its own enum implementing this interface.
 */
interface NotificationChannel
{
    public function value(): string;

    public function driverBinding(): string;
}
