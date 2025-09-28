<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Category;

use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Category;
use App\Services\Permissions\PermissionsService;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CategoryTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName     = 'index_category_datatable';
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
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'actions');
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
            ['label' => trans('general.page.index.title', ['model' => trans('category.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link'   => route('admin.category.create'),
                'icon'   => 's-plus',
                'label'  => trans(
                    'general.page.create.title',
                    ['model' => trans('category.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Category::query();
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
            ->add('image', fn ($row) => PowerGridHelper::fieldImage($row))
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('type_formatted', fn ($row) => $row->type->title())
            ->add('parent_id_formatted', fn ($row) => $row->parent ? $row->parent->title : '-')
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('view_count_formated', fn ($row) => "<strong style='color: " . ($row->view_count === 0 ? 'blue' : 'red') . "'>{$row->view_count}</strong>")
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnImage(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('datatable.type'), 'type_formatted', 'type'),
            Column::make(trans('datatable.parent_id'), 'parent_id_formatted', 'parent_id'),
            PowerGridHelper::columnPublished(),
            PowerGridHelper::columnViewCount('view_count_formated')->sortable()->hidden(true, false),
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

            Filter::enumSelect('type_formatted', 'type')
                ->dataSource(CategoryTypeEnum::cases()),
        ];
    }

    public function actions(Category $row): array
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
            'link' => route('admin.category.create'),
        ]);
    }
}
