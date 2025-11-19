<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionSystem;

use App\Actions\QuestionSystem\StoreQuestionSystemAction;
use App\Actions\QuestionSystem\UpdateQuestionSystemAction;
use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Category;
use App\Models\QuestionSystem;
use App\Traits\HasModalForm;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class QuestionSystemTable extends PowerGridComponent
{
    use HasModalForm;
    use PowerGridHelperTrait;
    use Toast;

    public string $tableName = 'index_questionSystem_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount()
                ->includeViewOnBottom('components.admin.shared.modal-form'),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'actions');
        }

        return $setup;
    }

    /** تعریف فیلدهای مودال */
    protected function modalFields(): array
    {
        return [
            [
                'name' => 'title',
                'type' => 'input',
                'label' => trans('validation.attributes.title'),
                'placeholder' => trans('validation.attributes.title'),
                'required' => true,
            ],
            [
                'name' => 'description',
                'type' => 'input',
                'label' => trans('validation.attributes.description'),
                'placeholder' => trans('validation.attributes.description'),
                'required' => true,
            ],
            [
                'name' => 'category_id',
                'type' => 'select',
                'label' => trans('validation.attributes.category'),
                'options' => Category::where('type', CategoryTypeEnum::QUESTION_SYSTEM)
                    ->get()
                    ->map(fn (Category $category) => [
                        'id' => $category->id,
                        'name' => $category->title,
                    ])
                    ->toArray(),
                'required' => true,
                'placeholder' => trans('general.please_select_an_option'),
            ],
            [
                'name' => 'published',
                'type' => 'toggle',
                'label' => trans('datatable.status'),
                'default' => false,
            ],
        ];
    }

    protected function getModelClass(): string
    {
        return QuestionSystem::class;
    }

    protected function getStoreActionClass(): string
    {
        return StoreQuestionSystemAction::class;
    }

    protected function getUpdateActionClass(): string
    {
        return UpdateQuestionSystemAction::class;
    }

    protected function getModelTranslationKey(): string
    {
        return 'questionSystem.model';
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('questionSystem.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['action' => 'openCreateModal', 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('questionSystem.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return QuestionSystem::query();
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
            ->add('category_formated', fn ($row) => $row->category?->title ?? '-')
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('datatable.category'), 'category_formated'),
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

    public function actions(QuestionSystem $row): array
    {
        return [
            PowerGridHelper::btnTranslate($row),
            PowerGridHelper::btnToggle($row),
            Button::add('edit')
                ->slot(PowerGridHelper::iconEdit())
                ->attributes([
                    'class' => 'btn btn-square md:btn-sm btn-xs',
                ])
                ->dispatch('openEditModal', ['id' => $row->id])
                ->tooltip(trans('datatable.buttons.edit')),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
        ]);
    }
}
