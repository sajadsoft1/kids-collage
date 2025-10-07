<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Helpers\Constants;
use App\Helpers\PowerGridHelper;
use App\Models\User;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class UserTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName     = 'user-index-h9omkb-table';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                     ->showSearchInput(),

            PowerGrid::footer()
                     ->showPerPage()
                     ->showRecordCount(),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'name', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('user.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.user.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('user.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return User::query();
    }

    public function relationSearch(): array
    {
        return [
            // 'name',
            // 'family',
            // 'email',
            // 'mobile'
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
                        ->add('id')
                        ->add('image', fn($row) => PowerGridHelper::fieldImage($row, 'image', Constants::RESOLUTION_854_480, 11, 6))
                        ->add('name')
                        ->add('email')
                        ->add('mobile')
                        ->add('status_formated', fn($row) => view('admin.datatable-shared.user-status', [
                            'row' => $row,
                        ]))
                        ->add('user_formated', fn($row) => view('admin.datatable-shared.user-info', [
                            'row' => $row,
                        ]))
                        ->add('gender_formated', fn($row) => view('admin.datatable-shared.gender', [
                            'gender' => $row->profile?->gender,
                        ]))
                        ->add('created_at_formatted', fn($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('validation.attributes.username'), 'user_formated', 'name'),

            Column::make(trans('validation.attributes.status'), 'status_formated', 'status')
                  ->sortable(),

            Column::make(trans('validation.attributes.gender'), 'gender_formated', 'gender')
                  ->sortable(),


            PowerGridHelper::columnCreatedAT(),

            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at_formatted', 'created_at')
                  ->params([
                      'maxDate' => now(),
                  ]),
        ];
    }

    public function actions(User $row): array
    {
        return [
            PowerGridHelper::btnToggle($row,'status'),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
