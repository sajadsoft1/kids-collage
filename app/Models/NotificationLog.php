<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Karnoweb\LaravelNotification\Models\NotificationLog as PackageNotificationLog;

class NotificationLog extends PackageNotificationLog
{
    use HasFactory;
}
