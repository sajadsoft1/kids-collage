<?php

namespace App\Actions\NotificationTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\NotificationTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreNotificationTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @return NotificationTemplate
     * @throws Throwable
     */
    public function handle(array $payload): NotificationTemplate
    {
        return DB::transaction(function () use ($payload) {
            $model =  NotificationTemplate::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
