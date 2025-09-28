<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Notification;

use App\Enums\BooleanEnum;
use App\Helpers\NotifyHelper;
use App\Helpers\PowerGridHelper;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class NotificationTable extends PowerGridComponent
{
    use PowerGridHelperTrait,Toast;

    public string $tableName     = 'index_notification_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()
                ->fixedColumns('id', 'title');
        }

        return $setup;
    }

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'page'   => ['except' => 1],
            ...$this->powerGridQueryString(),
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('notification.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
        ];
    }

    public function datasource(): Builder
    {
        return DatabaseNotification::query();
    }

    public function relationSearch(): array
    {
        return [
            'translations' => [
                'value',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('title', fn ($row) => NotifyHelper::title($row->data))
            ->add('sub_title', fn ($row) => Str::limit(NotifyHelper::subTitle($row->data), 30))
            ->add('user_name', fn ($row) => $row->notifiable->full_name)
            ->add('read_at_formated', fn ($row) => jdate($row->read_at)->format('%A, %d %B %Y'))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnTitle(),
            Column::make(trans('datatable.description'), 'sub_title'),
            Column::make(trans('datatable.user_name'), 'user_name'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('published_formated', 'published')
                ->datasource(BooleanEnum::cases()),

            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(DatabaseNotification $row): array
    {
        return [
            Button::add('delete')
                ->slot("<i class='fa fa-eye'></i>")
                ->dispatch('show-detail', ['rowId' => $row->id])
                ->tooltip('detail'),
        ];
    }

    #[On('show-detail')]
    public function showDetail($rowId): void
    {
        $model = $this->datasource()->getModel()::where('id', $rowId)->first();
        $this->info(
            title: NotifyHelper::subTitle($model->data),
        );
    }
}
