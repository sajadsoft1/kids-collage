<?php

declare(strict_types=1);

namespace App\Services\Sms\Contracts;

interface PingableSmsDriver
{
    /** Lightweight connectivity check. */
    public function ping(): bool;
}
