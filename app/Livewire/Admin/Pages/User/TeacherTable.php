<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Enums\UserTypeEnum;
use App\Helpers\Constants;
use App\Helpers\PowerGridHelper;
use App\Livewire\Admin\Pages\User\Concerns\HandlesPasswordChange;
use App\Models\User;
use App\Services\Permissions\PermissionsService;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class TeacherTable extends PowerGridComponent
{
    use HandlesPasswordChange;
    use PowerGridHelperTrait;

    public string $tableName = 'teacher-index-h9omkb-table';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'name', 'actions'];
    }

    protected function afterPowerGridSetUp(array &$setup): void
    {
        $setup[0] = PowerGrid::header()
            ->showToggleColumns()
            ->showSearchInput()
            ->includeViewOnBottom('livewire.admin.pages.user.partials.change-password-modal');
    }

    public function datasource(): Builder
    {
        return User::query()->where('type', UserTypeEnum::TEACHER);
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
            ->add('image', fn ($row) => PowerGridHelper::fieldImage($row, 'image', Constants::RESOLUTION_854_480, 11, 6))
            ->add('name')
            ->add('email')
            ->add('mobile')
            ->add('status_formated', fn ($row) => view('admin.datatable-shared.user-status', [
                'row' => $row,
            ]))
            ->add('user_formated', fn ($row) => view('admin.datatable-shared.user-info', [
                'row' => $row,
            ]))
            ->add('gender_formated', fn ($row) => view('admin.datatable-shared.gender', [
                'gender' => $row->profile?->gender,
            ]))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
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
            PowerGridHelper::btnToggle($row, 'status'),
            PowerGridHelper::btnChangePassword($row),
            Button::add('edit')
                ->slot("<i class='fa fa-pencil'></i>")
                ->attributes([
                    'class' => 'btn btn-square md:btn-sm btn-xs',
                ])
                ->can(Auth::user()?->hasAnyPermission(PermissionsService::generatePermissionsByModel($row::class, 'Update')) ?? false)
                ->route('admin.teacher.edit', ['user' => $row->id], '_self')
                ->navigate()
                ->tooltip(trans('datatable.buttons.edit')),
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
