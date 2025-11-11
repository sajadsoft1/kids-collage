<?php

declare(strict_types=1);

namespace App\Actions\NotificationTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateNotificationTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * Update an existing notification template
     *
     * @param array{
     *     name:string,
     *     channel:string,
     *     message_template:string,
     *     languages:array|null,
     *     inputs:array|null,
     *     published:bool
     * } $payload
     * @throws Throwable
     */
    public function handle(NotificationTemplate $notificationTemplate, array $payload): NotificationTemplate
    {
        return DB::transaction(function () use ($notificationTemplate, $payload) {
            // Update the notification template
            $notificationTemplate->update([
                'name' => $payload['name'],
                'channel' => $payload['channel'],
                'message_template' => $payload['message_template'],
                'languages' => $payload['languages'] ?? [],
                'inputs' => $payload['inputs'] ?? [],
                'published' => $payload['published'] ?? false,
            ]);

            return $notificationTemplate->refresh();
        });
    }
}
