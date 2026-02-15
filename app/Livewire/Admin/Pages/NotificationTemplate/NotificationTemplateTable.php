<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\NotificationTemplate;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Helpers\PowerGridHelper;
use Karnoweb\LaravelNotification\Models\NotificationTemplate;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * NotificationTemplateTable Component
 *
 * Displays a data table of notification templates with filtering,
 * sorting, and CRUD actions.
 */
final class NotificationTemplateTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName = 'index_notificationTemplate_datatable';
    public string $sortDirection = 'desc';

    public function beforePowerGridSetUp(): void
    {
        $this->persistItems = ['columns', 'sort'];
    }

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'name', 'actions'];
    }

    /** Breadcrumb navigation items */
    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('general.notification_template')])],
        ];
    }

    /** Breadcrumb action buttons */
    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
        ];
    }

    /** Query builder for data source */
    public function datasource(): Builder
    {
        return NotificationTemplate::query();
    }

    /** Relation search configuration (not used for this model) */
    public function relationSearch(): array
    {
        return [];
    }

    /** Define table fields */
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('event_label', fn (NotificationTemplate $row) => NotificationEventEnum::tryFrom($row->event)?->title() ?? $row->event)
            ->add('channel_label', fn (NotificationTemplate $row) => NotificationChannelEnum::tryFrom($row->channel)?->title() ?? $row->channel)
            ->add('locale')
            ->add('is_active', fn (NotificationTemplate $row) => $row->is_active ? trans('general.active') : trans('general.inactive'))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    /** Define table columns */
    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),

            Column::make(trans('notificationTemplate.fields.event'), 'event_label', 'event')
                ->sortable()
                ->searchable(),

            Column::make(trans('notificationTemplate.fields.channel'), 'channel_label', 'channel')
                ->sortable()
                ->searchable(),

            Column::make(trans('notificationTemplate.fields.locale'), 'locale')
                ->sortable()
                ->searchable(),

            Column::make(trans('notificationTemplate.fields.is_active'), 'is_active')->sortable(),

            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    /** Define table filters */
    public function filters(): array
    {
        $eventOptions = collect(NotificationEventEnum::cases())->map(fn (NotificationEventEnum $event) => [
            'id' => $event->value,
            'name' => $event->title(),
        ]);

        $channelOptions = collect(NotificationChannelEnum::cases())
            ->filter(fn (NotificationChannelEnum $channel) => ! $channel->isFutureChannel())
            ->map(fn (NotificationChannelEnum $channel) => [
                'id' => $channel->value,
                'name' => $channel->title(),
            ]);

        return [
            Filter::select('event', 'event')
                ->dataSource($eventOptions->toArray())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('channel', 'channel')
                ->dataSource($channelOptions->toArray())
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('is_active', 'is_active')
                ->dataSource([
                    ['id' => 1, 'name' => trans('general.active')],
                    ['id' => 0, 'name' => trans('general.inactive')],
                ])
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('locale', 'locale')
                ->dataSource([
                    ['id' => 'fa', 'name' => 'فارسی'],
                    ['id' => 'en', 'name' => 'انگلیسی'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),

            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    /** Define row actions */
    public function actions(NotificationTemplate $row): array
    {
        return [
            // PowerGridHelper::btnToggle($row, 'is_active'),
            PowerGridHelper::btnEdit($row),
            // PowerGridHelper::btnDelete($row),
        ];
    }

    /** Display empty table message */
    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
        ]);
    }
}
