<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\License;

use App\Helpers\Constants;
use App\Helpers\PowerGridHelper;
use App\Models\License;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class LicenseTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_license_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $this->persist(['columns'], prefix: auth()->id ?? '');
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showToggleColumns()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()
                ->fixedColumns('id', 'title', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('license.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link' => route('admin.license.create'),
                'icon' => 's-plus',
                'label' => trans(
                    'general.page.create.title',
                    ['model' => trans('license.model')]
                ),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return License::query();
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
            ->add('id')
            ->add('image', fn ($row) => PowerGridHelper::fieldImage($row, 'image', Constants::RESOLUTION_854_480, 11, 6))
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('view_count_formated', fn ($row) => "<strong style='color: " . ($row->view_count === 0 ? 'blue' : 'red') . "'>{$row->view_count}</strong>")
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row))
            ->add('updated_at_formatted', fn ($row) => PowerGridHelper::fieldUpdatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnImage(),
            PowerGridHelper::columnTitle(),
            PowerGridHelper::columnViewCount('view_count_formated')->hidden(true, false),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnUpdatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(License $row): array
    {
        return [
            PowerGridHelper::btnSeo($row),
            PowerGridHelper::btnTranslate($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.license.create'),
        ]);
    }
}
