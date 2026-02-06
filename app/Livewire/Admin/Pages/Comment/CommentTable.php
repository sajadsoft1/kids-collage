<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Comment;

use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
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

final class CommentTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName = 'index_comment_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('comment.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link' => route('admin.comment.create'),
                'icon' => 's-plus',
                'label' => trans(
                    'general.page.create.title',
                    ['model' => trans('comment.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Comment::with(['translations']);
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
            ->add('morphable_type_formatted', fn ($row) => $row->morphable_type == Blog::class ? trans('blog.model') : '---')
            ->add('morphable_id_formatted', fn ($row) => $row->morphable_type::find($row->morphable_id)->title)
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('datatable.category_title'), 'morphable_type_formatted'),
            Column::make(trans('datatable.title'), 'morphable_id_formatted'),
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
            Filter::select('morphable_type_formatted', 'morphable_type')
                ->dataSource([
                    [
                        'label' => 'مقاله',
                        'value' => Blog::class,
                    ],
                    [
                        'label' => 'دسته بندی',
                        'value' => Category::class,
                    ],
                ])->optionValue('value')->optionLabel('label'),
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(Comment $row): array
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
            'link' => route('admin.comment.create'),
        ]);
    }
}
