<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Notification;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;
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

    public string $tableName = 'index_notification_datatable';
    public string $sortDirection = 'desc';

    /** Modal state and selected notification data. */
    public bool $showDetailModal = false;
    public ?string $detailTitle = null;
    public ?string $detailBody = null;

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title'];
    }

    protected function afterPowerGridSetUp(array &$setup): void
    {
        $setup[1]->includeViewOnBottom('admin.custom-views.notification-table');
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
            ->add('title', fn ($row) => is_null($row->read_at) ? "<strong class='font-bold text-black'>{$row->data['title']}</strong>" : $row->data['title'])
            ->add('sub_title', fn ($row) => $row->data['sub_title'])
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
            Button::add('show')
                ->slot(
                    is_null($row->read_at)
                    ? "<i class='fa fa-eye text-primary'></i>"
                    : "<i class='fa fa-eye'></i>"
                )
                ->dispatch('show-detail', ['rowId' => $row->id])
                ->tooltip('detail'),
        ];
    }

    #[On('show-detail')]
    public function showDetail(string $rowId): void
    {
        /** @var DatabaseNotification|null $model */
        $model = $this->datasource()->getModel()::query()
            ->whereKey($rowId)
            ->first();

        if ( ! $model) {
            $this->error(
                title: trans('general.error'),
                description: trans('general.record_not_found'),
            );

            return;
        }
        $model->read_at = now();
        $model->save();

        $this->detailTitle = data_get($model->data, 'title');
        $this->detailBody = data_get($model->data, 'body');
        $this->showDetailModal = true;
    }
}
