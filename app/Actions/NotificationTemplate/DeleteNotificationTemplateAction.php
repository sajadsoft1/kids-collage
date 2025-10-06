<?php

namespace App\Actions\NotificationTemplate;

use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteNotificationTemplateAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(NotificationTemplate $notificationTemplate): bool
    {
        return DB::transaction(function () use ($notificationTemplate) {
            return $notificationTemplate->delete();
        });
    }
}
