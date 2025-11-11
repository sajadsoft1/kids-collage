<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\NotificationTemplate;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\NotificationTemplate;
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

    public string $tableName     = 'index_notificationTemplate_datatable';
    public string $sortDirection = 'desc';

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
            ['link' => route('admin.notification-template.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('general.notification_template')])],
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
            ->add('name')
            ->add('channel', function ($row) {
                return match ($row->channel) {
                    'sms'          => '<span class="badge badge-primary badge-sm">' . trans('general.channels.sms') . '</span>',
                    'email'        => '<span class="badge badge-info badge-sm">' . trans('general.channels.email') . '</span>',
                    'notification' => '<span class="badge badge-accent badge-sm">' . trans('general.channels.notification') . '</span>',
                    default        => $row->channel,
                };
            })
            ->add('channel_text', fn ($row) => trans('general.channels.' . $row->channel))
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    /** Define table columns */
    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),

            Column::make(trans('validation.attributes.name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(trans('validation.attributes.channel'), 'channel', 'channel_text')
                ->sortable()
                ->searchable(),

            PowerGridHelper::columnPublished(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    /** Define table filters */
    public function filters(): array
    {
        return [
            Filter::select('channel_text', 'channel')
                ->dataSource([
                    ['id' => 'sms', 'name' => trans('general.channels.sms')],
                    ['id' => 'email', 'name' => trans('general.channels.email')],
                    ['id' => 'notification', 'name' => trans('general.channels.notification')],
                ])
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::enumSelect('published_formated', 'published')
                ->datasource(BooleanEnum::cases()),

            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    /** Define row actions */
    public function actions(NotificationTemplate $row): array
    {
        return [
            PowerGridHelper::btnToggle($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    /** Display empty table message */
    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.notification-template.create'),
        ]);
    }
}
