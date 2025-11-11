<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Slider;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Slider;
use App\Services\Permissions\PermissionsService;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class SliderTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName     = 'index_slider_datatable';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('slider.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link' => route('admin.slider.create'),
                'icon' => 's-plus',
                'label' => trans(
                    'general.page.create.title',
                    ['model' => trans('slider.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Slider::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Slider::query();
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
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('expired_formated', fn ($row) => PowerGridHelper::fieldDateFormated($row, 'expired_at'))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('discount.page.fields.expires_at'), 'expired_formated', 'expired_at')
                ->sortable(),
            PowerGridHelper::columnPublished(),
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

    public function actions(Slider $row): array
    {
        return [
            PowerGridHelper::btnTranslate($row),
            PowerGridHelper::btnToggle($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.slider.create'),
        ]);
    }
}
