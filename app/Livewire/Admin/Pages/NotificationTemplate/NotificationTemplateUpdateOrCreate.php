<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\NotificationTemplate;

use App\Actions\NotificationTemplate\StoreNotificationTemplateAction;
use App\Actions\NotificationTemplate\UpdateNotificationTemplateAction;
use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Models\NotificationTemplate;
use App\Traits\CrudHelperTrait;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use RuntimeException;
use Throwable;

/**
 * NotificationTemplateUpdateOrCreate Component
 *
 * Handles creating and updating notification templates with support for
 * multiple channels (SMS, Email, Notification) and languages.
 */
class NotificationTemplateUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public NotificationTemplate $model;

    /** Form fields */
    public string $event = '';
    public string $channel = '';
    public string $locale = '';
    public ?string $subject = null;
    public ?string $title = null;
    public ?string $subtitle = null;
    public ?string $body = null;
    public array $placeholders = [];
    public bool $is_active = true;
    public ?string $cta_label = null;
    public ?string $cta_url = null;

    /** Options */
    public array $eventOptions = [];
    public array $channelOptions = [];
    public array $localeOptions = [];

    /** Mount the component with the notification template model */
    public function mount(NotificationTemplate $notificationTemplate): void
    {
        $this->model = $notificationTemplate;

        $this->bootOptions();
        $this->fillDefaults();
    }

    /** Validation rules for the form */
    protected function rules(): array
    {
        $channelValues = collect($this->channelOptions)->pluck('id')->all();
        $localeValues = collect($this->localeOptions)->pluck('id')->all();
        $eventValues = collect($this->eventOptions)->pluck('id')->all();

        return [
            'event' => ['required', Rule::in($eventValues)],
            'channel' => ['required', Rule::in($channelValues)],
            'locale' => ['required', Rule::in($localeValues)],
            'subject' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'placeholders' => 'array',
            'placeholders.*' => 'string|max:255',
            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|url|max:2048',
            'is_active' => 'required|boolean',
        ];
    }

    /** Submit the form and create/update notification template */
    public function submit(): void
    {
        $payload = $this->validate();
        $this->validateUniqueCombination($payload);

        $cta = null;
        if ($this->cta_label || $this->cta_url) {
            $cta = [
                'label' => $this->cta_label,
                'url' => $this->cta_url,
            ];
        }

        $data = [
            'event' => $payload['event'],
            'channel' => $payload['channel'],
            'locale' => $payload['locale'],
            'subject' => $payload['subject'] ?? null,
            'title' => $payload['title'] ?? null,
            'subtitle' => $payload['subtitle'] ?? null,
            'body' => $payload['body'] ?? null,
            'placeholders' => $payload['placeholders'] ?? [],
            'cta' => $cta,
            'is_active' => $payload['is_active'],
        ];

        try {
            if ($this->model->exists) {
                UpdateNotificationTemplateAction::run($this->model, $data);
                $message = trans('general.model_has_updated_successfully', ['model' => trans('general.notification_template')]);
            } else {
                StoreNotificationTemplateAction::run($data);
                $message = trans('general.model_has_stored_successfully', ['model' => trans('general.notification_template')]);
            }

            $this->success(
                title: $message,
                redirectTo: route('admin.notification-template.index')
            );
        } catch (Throwable $throwable) {
            $this->error($throwable->getMessage(), timeout: 5000);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.notificationTemplate.notificationTemplate-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.notification-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('notificationTemplate.model')])],
                ['label' => $this->model->exists
                    ? trans('general.page.edit.title', ['model' => trans('notificationTemplate.model')])
                    : trans('general.page.create.title', ['model' => trans('notificationTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.notification-template.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }

    private function bootOptions(): void
    {
        $this->eventOptions = collect(NotificationEventEnum::cases())->map(fn (NotificationEventEnum $event) => [
            'id' => $event->value,
            'name' => $event->title(),
            'description' => $event->description(),
        ])->toArray();

        $this->channelOptions = collect(NotificationChannelEnum::cases())
            ->filter(fn (NotificationChannelEnum $channel) => ! $channel->isFutureChannel())
            ->map(fn (NotificationChannelEnum $channel) => [
                'id' => $channel->value,
                'name' => $channel->title(),
            ])->values()->toArray();

        $supportedLocales = config('locales.supported', ['fa', 'en']);
        $this->localeOptions = collect($supportedLocales)->map(fn (string $locale) => [
            'id' => $locale,
            'name' => strtoupper($locale),
        ])->toArray();
    }

    private function fillDefaults(): void
    {
        $this->event = $this->model->event ?? $this->eventOptions[0]['id'] ?? '';
        $this->channel = $this->model->channel ?? $this->channelOptions[0]['id'] ?? '';
        $this->locale = $this->model->locale ?? $this->localeOptions[0]['id'] ?? '';
        $this->subject = $this->model->subject ?? null;
        $this->title = $this->model->title ?? null;
        $this->subtitle = $this->model->subtitle ?? null;
        $this->body = $this->model->body ?? null;
        $this->placeholders = $this->model->placeholders ?? [];
        $this->is_active = $this->model->is_active ?? true;

        $cta = Arr::wrap($this->model->cta);
        $this->cta_label = Arr::get($cta, 'label');
        $this->cta_url = Arr::get($cta, 'url');
    }

    private function validateUniqueCombination(array $payload): void
    {
        $query = NotificationTemplate::query()
            ->where('event', $payload['event'])
            ->where('channel', $payload['channel'])
            ->where('locale', $payload['locale']);

        if ($this->model->exists) {
            $query->whereKeyNot($this->model->getKey());
        }

        if ($query->exists()) {
            $this->addError('locale', trans('validation.unique', ['attribute' => trans('notificationTemplate.fields.locale')]));

            throw new RuntimeException(trans('notificationTemplate.messages.duplicate_event_channel_locale'));
        }
    }
}
