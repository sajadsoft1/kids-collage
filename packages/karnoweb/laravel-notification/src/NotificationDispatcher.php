<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Karnoweb\LaravelNotification\Messages\NotificationMessage;
use Karnoweb\LaravelNotification\Models\NotificationLog;
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
        ?object $profile = null,
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
        string $event,
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
                'event' => $event,
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
        string $event,
        array $payload,
        array $context
    ): NotificationLog {
        $timestamp = Date::now();
        $logModelClass = config('karnoweb-notification.log_model', NotificationLog::class);

        return $logModelClass::query()->create([
            'event' => $event,
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
        $queuedChannels = config('karnoweb-notification.queued_channels', ['email', 'sms']);

        return in_array($channel->value, $queuedChannels, true);
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     */
    private function queueChannel(
        NotificationChannelEnum $channel,
        NotificationLog $log,
        string $event,
        array $payload,
        array $context
    ): void {
        $jobClasses = config('karnoweb-notification.queue_jobs', []);

        $jobClass = $jobClasses[$channel->value] ?? null;

        if ($jobClass === null || ! class_exists($jobClass)) {
            return;
        }

        $job = new $jobClass(
            $log->id,
            $event,
            $log->notifiable_type,
            $log->notifiable_id,
            $payload,
            $context
        );

        dispatch($job);
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
