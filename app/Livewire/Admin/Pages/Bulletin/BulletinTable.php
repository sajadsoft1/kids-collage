<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Bulletin;

use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Enums\RoleEnum;
use App\Helpers\Constants;
use App\Helpers\PowerGridHelper;
use App\Models\Bulletin;
use App\Models\Category;
use App\Models\User;
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

final class BulletinTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_bulletin_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
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
            ['label' => trans('general.page.index.title', ['model' => trans('bulletin.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link'   => route('admin.bulletin.create'),
                'icon'   => 's-plus',
                'label'  => trans(
                    'general.page.create.title',
                    ['model' => trans('bulletin.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Bulletin::query()
            ->with(['user', 'category']);
    }

    public function relationSearch(): array
    {
        return [
            'translations'          => [
                'value',
            ],
            'category.translations' => [
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
            ->add('category_formatted', fn ($row) => $row->category?->title ?? '---')
            ->add('author', fn ($row) => $row->user->name)
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
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
            Column::make(trans('datatable.category_title'), 'category_formatted'),
            Column::make(trans('datatable.author'), 'author'),
            PowerGridHelper::columnPublished(),
            PowerGridHelper::columnViewCount('view_count_formated')->hidden(true, false),
            PowerGridHelper::columnUpdatedAT(),
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

            Filter::select('category_formatted', 'category_id')
                ->dataSource(Category::where('type', CategoryTypeEnum::BULLETIN->value)->get()->map(function ($category) {
                    return [
                        'value' => $category->id,
                        'label' => $category->title,
                    ];
                })->toArray())->optionLabel('label')->optionValue('value'),

            Filter::select('author', 'user_id')
                ->dataSource(User::whereHas('roles', function (Builder $query) {
                    $query->where('name', RoleEnum::ADMIN->value);
                })->get()->map(function ($user) {
                    return [
                        'value' => $user->id,
                        'label' => $user->name,
                    ];
                })->toArray())->optionLabel('label')->optionValue('value'),
        ];
    }

    public function actions(Bulletin $row): array
    {
        return [
            PowerGridHelper::btnSeo($row),
            PowerGridHelper::btnTranslate($row),
            PowerGridHelper::btnToggle($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.bulletin.create'),
        ]);
    }
}
