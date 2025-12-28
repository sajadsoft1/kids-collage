<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Category;

use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Category;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CategoryTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $type = CategoryTypeEnum::BLOG->value;

    public string $tableName = 'index_category_datatable';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title', 'actions'];
    }

    protected function afterPowerGridSetUp(array &$setup): void
    {
        $setup[0] = PowerGrid::header()
            ->showToggleColumns()
            ->showSearchInput();
    }

    public function datasource(): Builder
    {
        return Category::where('type', $this->type);
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
