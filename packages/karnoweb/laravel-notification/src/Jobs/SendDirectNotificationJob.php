<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Karnoweb\LaravelNotification\NotificationService;

class SendDirectNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param array<string, array<string, mixed>> $payloadByChannel
     * @param array<string, mixed>                $context
     */
    public function __construct(
        protected string $notifiableType,
        protected string|int $notifiableId,
        protected string $event,
        protected array $payloadByChannel,
        protected ?string $profileType = null,
        protected string|int|null $profileId = null,
        protected array $context = [],
    ) {}

    public static function for(
        object $notifiable,
        string $event,
        array $payloadByChannel,
        ?object $profile = null,
        array $context = []
    ): self {
        $notifiableType = $notifiable->getMorphClass();
        $notifiableId = $notifiable->getKey();
        $profileType = null;
        $profileId = null;
        if ($profile !== null && method_exists($profile, 'getMorphClass') && method_exists($profile, 'getKey')) {
            $profileType = $profile->getMorphClass();
            $profileId = $profile->getKey();
        }

        return new self(
            $notifiableType,
            $notifiableId,
            $event,
            $payloadByChannel,
            $profileType,
            $profileId,
            $context
        );
    }

    public function handle(NotificationService $service): void
    {
        $notifiable = $this->resolveNotifiable();
        if ($notifiable === null) {
            return;
        }

        $profile = $this->resolveProfile();

        $service->sendDirect($notifiable, $this->event, $this->payloadByChannel, $profile, $this->context);
    }

    private function resolveNotifiable(): ?object
    {
        if ( ! is_subclass_of($this->notifiableType, Model::class)) {
            return null;
        }

        /** @var class-string<Model> $model */
        $model = $this->notifiableType;

        return $model::query()->find($this->notifiableId);
    }

    private function resolveProfile(): ?object
    {
        if ($this->profileType === null || $this->profileId === null) {
            return null;
        }

        if ( ! is_subclass_of($this->profileType, Model::class)) {
            return null;
        }

        /** @var class-string<Model> $model */
        $model = $this->profileType;

        return $model::query()->find($this->profileId);
    }
}
