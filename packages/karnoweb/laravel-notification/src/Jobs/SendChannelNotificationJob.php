<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Karnoweb\LaravelNotification\Contracts\NotificationChannel;
use Karnoweb\LaravelNotification\Models\NotificationLog;
use Karnoweb\LaravelNotification\NotificationChannelRegistry;
use Throwable;

abstract class SendChannelNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     */
    public function __construct(
        protected int $logId,
        protected string $eventValue,
        protected string $notifiableType,
        protected string|int|null $notifiableId,
        protected array $payload,
        protected array $context = [],
    ) {}

    abstract protected function channel(): NotificationChannel;

    public function handle(NotificationChannelRegistry $registry): void
    {
        $logModelClass = config('karnoweb-notification.log_model', NotificationLog::class);
        $log = $logModelClass::query()->find($this->logId);

        if ( ! $log) {
            return;
        }

        $log->forceFill([
            'status' => 'processing',
        ])->save();

        $notifiable = $this->resolveNotifiable();

        if ( ! $notifiable) {
            $log->forceFill([
                'status' => 'failed',
                'failed_at' => Date::now(),
                'error_message' => 'Notifiable model not found.',
            ])->save();

            return;
        }

        $driver = $registry->resolveDriver($this->channel());

        try {
            $response = $driver->send($notifiable, $this->eventValue, $this->payload, $this->context);

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

            Log::error('Queued notification channel failed', [
                'channel' => $this->channel()->value(),
                'event' => $this->eventValue,
                'notifiable_type' => $this->notifiableType,
                'notifiable_id' => $this->notifiableId,
                'exception' => $throwable,
            ]);
        }
    }

    private function resolveNotifiable(): ?object
    {
        if (is_subclass_of($this->notifiableType, Model::class)) {
            /** @var class-string<Model> $modelClass */
            $modelClass = $this->notifiableType;

            return $modelClass::query()->find($this->notifiableId);
        }

        return null;
    }
}
