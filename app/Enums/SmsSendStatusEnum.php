<?php

declare(strict_types=1);

namespace App\Enums;

enum SmsSendStatusEnum: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
}
