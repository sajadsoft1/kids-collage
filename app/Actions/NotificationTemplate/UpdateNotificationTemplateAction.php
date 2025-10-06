<?php

namespace App\Actions\NotificationTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\NotificationTemplate;
use Illuminate\Support\Arr;
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
     * @param NotificationTemplate $notificationTemplate
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return NotificationTemplate
     * @throws Throwable
     */
    public function handle(NotificationTemplate $notificationTemplate, array $payload): NotificationTemplate
    {
        return DB::transaction(function () use ($notificationTemplate, $payload) {
            $notificationTemplate->update($payload);
            $this->syncTranslationAction->handle($notificationTemplate, Arr::only($payload, ['title', 'description']));

            return $notificationTemplate->refresh();
        });
    }
}
