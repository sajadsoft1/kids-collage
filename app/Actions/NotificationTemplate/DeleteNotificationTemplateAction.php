<?php

declare(strict_types=1);

namespace App\Actions\NotificationTemplate;

use Karnoweb\LaravelNotification\Models\NotificationTemplate;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteNotificationTemplateAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(NotificationTemplate $notificationTemplate): bool
    {
        abort(401, 'You are not authorized to delete this notification template');
    }
}
