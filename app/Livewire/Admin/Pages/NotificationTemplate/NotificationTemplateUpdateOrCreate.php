<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\NotificationTemplate;

use App\Actions\NotificationTemplate\StoreNotificationTemplateAction;
use App\Actions\NotificationTemplate\UpdateNotificationTemplateAction;
use App\Models\NotificationTemplate;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
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

    // Form fields
    public string $name = '';
    public string $channel = 'sms';
    public string $message_template = '';
    public array $languages = [];
    public array $inputs = [];
    public bool $published = false;

    // Available options
    public array $channelOptions = [];
    public array $languageOptions = [];

    /** Mount the component with the notification template model */
    public function mount(NotificationTemplate $notificationTemplate): void
    {
        $this->model = $notificationTemplate;

        // Setup channel options
        $this->channelOptions = [
            ['id' => 'sms', 'name' => trans('general.channels.sms')],
            ['id' => 'email', 'name' => trans('general.channels.email')],
            ['id' => 'notification', 'name' => trans('general.channels.notification')],
        ];

        // Setup language options from config
        $supportedLocales = config('locales.supported', ['en', 'fa']);
        $this->languageOptions = collect($supportedLocales)->map(fn ($locale) => [
            'id' => $locale,
            'name' => trans('general.languages.' . $locale),
        ])->toArray();

        // Populate fields if editing
        if ($this->model->id) {
            $this->name = $this->model->name ?? '';
            $this->channel = $this->model->channel ?? 'sms';
            $this->message_template = $this->model->message_template ?? '';
            $this->languages = $this->model->languages ?? [];
            $this->inputs = $this->model->inputs ?? [];
            $this->published = $this->model->published->asBoolean();
        }
    }

    /** Validation rules for the form */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'channel' => 'required|string|in:sms,email,notification',
            'message_template' => 'required|string|min:10',
            'languages' => 'nullable|array',
            'inputs' => 'nullable|array',
            'published' => 'required|boolean',
        ];
    }

    /** Submit the form and create/update notification template */
    public function submit(): void
    {
        $payload = $this->validate();

        if ($this->model->id) {
            try {
                UpdateNotificationTemplateAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('general.notification_template')]),
                    redirectTo: route('admin.notificationTemplate.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreNotificationTemplateAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('general.notification_template')]),
                    redirectTo: route('admin.notificationTemplate.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.notificationTemplate.notificationTemplate-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.notification-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('notificationTemplate.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('notificationTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.notification-template.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
