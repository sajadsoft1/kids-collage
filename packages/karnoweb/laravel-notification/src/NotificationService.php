<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification;

use Karnoweb\LaravelNotification\Jobs\SendDirectNotificationJob;
use Karnoweb\LaravelNotification\Messages\NotificationMessage;

class NotificationService
{
    public function __construct(
        private readonly NotificationDispatcher $dispatcher,
    ) {}

    /**
     * Send notification directly without a Notification class.
     * Builds message from payload and dispatches (queued channels are still queued).
     *
     * @param array<string, array<string, mixed>> $payloadByChannel channel value => payload (e.g. ['database' => ['title' => '...', 'body' => '...'], 'email' => ['subject' => '...']])
     * @param array<string, mixed>                $context
     */
    public function sendDirect(
        object $notifiable,
        string $event,
        array $payloadByChannel,
        ?object $profile = null,
        array $context = []
    ): void {
        $message = NotificationMessage::make($event)->withContext($context);

        foreach ($payloadByChannel as $channelValue => $payload) {
            $channel = NotificationChannelEnum::tryFrom($channelValue);
            if ($channel !== null && ! empty($payload)) {
                $message = $message->withChannel($channel, $payload);
            }
        }

        $this->dispatcher->dispatch($notifiable, $message, $profile, $context);
    }

    /**
     * Queue the entire send (dispatcher runs in a job).
     *
     * @param array<string, array<string, mixed>> $payloadByChannel
     * @param array<string, mixed>                $context
     */
    public function queueSendDirect(
        object $notifiable,
        string $event,
        array $payloadByChannel,
        ?object $profile = null,
        array $context = []
    ): void {
        $job = SendDirectNotificationJob::for($notifiable, $event, $payloadByChannel, $profile, $context);
        dispatch($job);
    }
}
