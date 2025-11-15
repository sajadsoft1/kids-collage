<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Models\NotificationTemplate;
use App\Models\Profile;
use App\Support\Notifications\Messages\NotificationMessage;
use App\Support\Notifications\NotificationDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        // استفاده از صف پیش‌فرض - اگر نیاز به صف جداگانه دارید، queue worker را با --queue=notifications اجرا کنید
        // $this->onQueue(config('notification_channels.defaults.queue', 'notifications'));
    }

    abstract public function event(): NotificationEventEnum;

    /** @return array<string, mixed> */
    protected function context(object $notifiable): array
    {
        return [];
    }

    public function via(object $notifiable): array
    {
        // اضافه کردن 'database' برای اینکه Laravel notification را در صف قرار دهد و از toArray() استفاده کند
        // سیستم custom از deliver() استفاده می‌کند که خودش notification را ذخیره می‌کند
        return ['database'];
    }

    /** @return array<string, string> */
    public function viaQueues(): array
    {
        // استفاده از صف پیش‌فرض - اگر نیاز به صف جداگانه دارید، از onQueue() در constructor استفاده کنید
        // $queue = config('notification_channels.defaults.queue', 'notifications');

        return [
            'mail' => 'default',
            'database' => 'default',
        ];
    }

    public function deliver(object $notifiable, ?Profile $profile = null, array $context = []): void
    {
        $message = $this->buildMessage($notifiable);
        $dispatcher = app(NotificationDispatcher::class);

        $dispatcher->dispatch(
            $notifiable,
            $message,
            $profile,
            array_merge($context, ['notification_class' => static::class])
        );
    }

    public function toArray(object $notifiable): array
    {
        $context = $this->context($notifiable);

        return $this->buildPayload(NotificationChannelEnum::DATABASE, $context);
    }

    public function buildMessage(object $notifiable): NotificationMessage
    {
        $context = $this->context($notifiable);
        $message = NotificationMessage::make($this->event())->withContext($context);

        foreach ($this->supportedChannels() as $channel) {
            $payload = $this->buildPayload($channel, $context);

            if ( ! empty($payload)) {
                $message = $message->withChannel($channel, $payload);
            }
        }

        return $message;
    }

    /** @return array<string, mixed> */
    protected function buildPayload(NotificationChannelEnum $channel, array $context): array
    {
        $template = $this->resolveTemplate($channel, $context);

        if ( ! $template) {
            return [];
        }

        $placeholders = $template->placeholders ?? [];

        $meta = array_filter([
            'event' => $this->event()->value,
            'channel' => $channel->value,
            'locale' => App::getLocale(),
        ]);

        return array_filter([
            'subject' => $this->replace($template->subject, $placeholders, $context),
            'title' => $this->replace($template->title, $placeholders, $context),
            'subtitle' => $this->replace($template->subtitle, $placeholders, $context),
            'sub_title' => $this->replace($template->subtitle, $placeholders, $context),
            'body' => $this->replace($template->body, $placeholders, $context),
            'cta' => $template->cta ? $this->replaceArray($template->cta, $placeholders, $context) : null,
            'placeholders' => $template->placeholders ?? [],
            'meta' => array_merge($meta, $this->channelMeta($channel, $context)),
        ], static fn ($value) => $value !== null && $value !== '');
    }

    /** @return iterable<int, NotificationChannelEnum> */
    protected function supportedChannels(): iterable
    {
        return [
            NotificationChannelEnum::DATABASE,
            NotificationChannelEnum::EMAIL,
            NotificationChannelEnum::SMS,
        ];
    }

    private function resolveTemplate(NotificationChannelEnum $channel, array $context): ?NotificationTemplate
    {
        $preferredLocale = $context['locale']
            ?? $context['preferred_locale']
            ?? App::getLocale();

        return NotificationTemplate::query()
            ->active()
            ->where('event', $this->event()->value)
            ->where('channel', $channel->value)
            ->where('locale', $preferredLocale)
            ->first();
    }

    private function replace(?string $value, array $placeholders, array $context): ?string
    {
        if ($value === null) {
            return null;
        }

        $search = [];
        $replace = [];

        foreach ($placeholders as $placeholder) {
            $search[] = '{{' . $placeholder . '}}';
            $replace[] = Arr::get($context, $placeholder, '');
        }

        return str_replace($search, $replace, $value);
    }

    /** @param array<string, mixed> $cta */
    private function replaceArray(array $cta, array $placeholders, array $context): array
    {
        return collect($cta)
            ->map(fn ($value) => is_string($value) ? $this->replace($value, $placeholders, $context) : $value)
            ->toArray();
    }

    /**
     * @param  array<string, mixed> $context
     * @return array<string, mixed>
     */
    protected function channelMeta(NotificationChannelEnum $channel, array $context): array
    {
        return [];
    }
}
