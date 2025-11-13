<?php

declare(strict_types=1);

namespace App\Support\Notifications;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Jobs\Notifications\SendEmailNotificationJob;
use App\Jobs\Notifications\SendSmsNotificationJob;
use App\Models\NotificationLog;
use App\Models\Profile;
use App\Support\Notifications\Messages\NotificationMessage;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificationDispatcher
{
    public function __construct(
        private readonly NotificationChannelRegistry $registry,
        private readonly NotificationPreferenceResolver $preferenceResolver,
    ) {}

    /** @param array<string, mixed> $context */
    public function dispatch(
        object $notifiable,
        NotificationMessage $message,
        ?Profile $profile = null,
        array $context = []
    ): void {
        $event = $message->event;
        $channels = $this->preferenceResolver->enabledChannels($profile, $event);
        $mergedContext = array_merge($context, $message->context());

        foreach ($channels as $channel) {
            $payload = $message->channelPayload($channel);

            if (empty($payload)) {
                continue;
            }

            $this->sendThroughChannel($channel, $notifiable, $event, $payload, $mergedContext);
        }
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     */
    private function sendThroughChannel(
        NotificationChannelEnum $channel,
        object $notifiable,
        NotificationEventEnum $event,
        array $payload,
        array $context = []
    ): void {
        $log = $this->createLog($channel, $notifiable, $event, $payload, $context);

        if ($this->shouldQueue($channel)) {
            $this->queueChannel($channel, $log, $event, $payload, $context);

            return;
        }

        $log->forceFill([
            'status' => 'processing',
        ])->save();

        try {
            $driver = $this->registry->resolveDriver($channel);
            $response = $driver->send($notifiable, $event, $payload, $context);

            $log->forceFill([
                'status' => 'sent',
                'sent_at' => Date::now(),
                'response' => $response,
                'attempts' => $log->attempts + 1,
            ])->save();
        } catch (Throwable $throwable) {
            $log->forceFill([
                'status' => 'failed',
                'failed_at' => Date::now(),
                'error_message' => $throwable->getMessage(),
                'attempts' => $log->attempts + 1,
            ])->save();

            Log::error('Notification channel failed', [
                'channel' => $channel->value,
                'event' => $event->value,
                'notifiable_type' => $log->notifiable_type,
                'notifiable_id' => $log->notifiable_id,
                'exception' => $throwable,
            ]);
        }
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     */
    private function createLog(
        NotificationChannelEnum $channel,
        object $notifiable,
        NotificationEventEnum $event,
        array $payload,
        array $context
    ): NotificationLog {
        $timestamp = Date::now();

        return NotificationLog::query()->create([
            'event' => $event->value,
            'channel' => $channel->value,
            'notifiable_type' => $this->resolveNotifiableType($notifiable),
            'notifiable_id' => $this->resolveNotifiableKey($notifiable),
            'notification_class' => $context['notification_class'] ?? null,
            'status' => 'queued',
            'attempts' => 0,
            'queued_at' => $timestamp,
            'payload' => $payload,
            'response' => null,
            'error_message' => null,
        ]);
    }

    private function shouldQueue(NotificationChannelEnum $channel): bool
    {
        return in_array($channel, [NotificationChannelEnum::EMAIL, NotificationChannelEnum::SMS], true);
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     */
    private function queueChannel(
        NotificationChannelEnum $channel,
        NotificationLog $log,
        NotificationEventEnum $event,
        array $payload,
        array $context
    ): void {
        $job = match ($channel) {
            NotificationChannelEnum::EMAIL => new SendEmailNotificationJob(
                $log->id,
                $event->value,
                $log->notifiable_type,
                $log->notifiable_id,
                $payload,
                $context
            ),
            NotificationChannelEnum::SMS => new SendSmsNotificationJob(
                $log->id,
                $event->value,
                $log->notifiable_type,
                $log->notifiable_id,
                $payload,
                $context
            ),
            default => null,
        };

        if ($job) {
            dispatch($job);
        }
    }

    private function resolveNotifiableType(object $notifiable): string
    {
        if (method_exists($notifiable, 'getMorphClass')) {
            return $notifiable->getMorphClass();
        }

        return $notifiable::class;
    }

    private function resolveNotifiableKey(object $notifiable): int|string|null
    {
        if (method_exists($notifiable, 'getKey')) {
            return $notifiable->getKey();
        }

        return $notifiable->id ?? null;
    }
}
