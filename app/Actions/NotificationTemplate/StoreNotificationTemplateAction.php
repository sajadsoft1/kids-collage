<?php

declare(strict_types=1);

namespace App\Actions\NotificationTemplate;

use App\Models\NotificationTemplate;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreNotificationTemplateAction
{
    use AsAction;

    /**
     * Store a new notification template
     *
     * @param array{
     *     event:string,
     *     channel:string,
     *     locale:string,
     *     subject:string|null,
     *     title:string|null,
     *     subtitle:string|null,
     *     body:string|null,
     *     cta:array<string,mixed>|null,
     *     placeholders:array<int,string>|null,
     *     is_active:bool
     * } $payload
     */
    public function handle(array $payload): NotificationTemplate
    {
        $model = NotificationTemplate::create([
            'event' => $payload['event'],
            'channel' => $payload['channel'],
            'locale' => $payload['locale'],
            'subject' => $payload['subject'] ?? null,
            'title' => $payload['title'] ?? null,
            'subtitle' => $payload['subtitle'] ?? null,
            'body' => $payload['body'] ?? null,
            'cta' => $payload['cta'] ?? [],
            'placeholders' => $payload['placeholders'] ?? [],
            'is_active' => $payload['is_active'] ?? true,
        ]);

        return $model->refresh();
    }
}
