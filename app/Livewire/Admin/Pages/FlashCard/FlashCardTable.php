<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\FlashCard;

use App\Enums\BooleanEnum;
use App\Enums\UserTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\FlashCard;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class FlashCardTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_flashCard_datatable';
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
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'front', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('flashCard.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.flash-card.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('flashCard.model')])],
        ];
    }

    public function datasource(): Builder
    {
        $user = Auth::user();

        return FlashCard::query()->when(
            $user->type === UserTypeEnum::PARENT,
            function ($q) use ($user) {
                $children = $user->children->pluck('id')->toArray();
                $q->whereIn('user_id', [...$children, $user->id]);
            }
        )
            ->when(
                $user->type === UserTypeEnum::EMPLOYEE,
                function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            )
            ->when(
                $user->type === UserTypeEnum::USER,
                function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            );
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
            ->add('user_formated', fn ($row) => view('admin.datatable-shared.user-info', [
                'row' => $row->user,
            ]))
            ->add('front_formated', fn ($row) => Str::limit($row->front, 20))
            ->add('favorite_formated', fn ($row) => $row->favorite == BooleanEnum::ENABLE ? trans('datatable.yes') : trans('datatable.no'))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('datatable.user_name'), 'user_formated', 'user_formated'),
            Column::make(trans('flashCard.fields.front'), 'front_formated', 'front')
                ->sortable()
                ->searchable(),
            Column::make(trans('datatable.favorite'), 'favorite_formated'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('published_formated', 'published')
                ->datasource(BooleanEnum::cases()),

            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(FlashCard $row): array
    {
        return [
            PowerGridHelper::btnToggle($row, 'favorite'),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.flash-card.create'),
        ]);
    }
}
