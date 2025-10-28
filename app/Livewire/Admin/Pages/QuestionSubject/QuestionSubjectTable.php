<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionSubject;

use App\Actions\QuestionSubject\StoreQuestionSubjectAction;
use App\Actions\QuestionSubject\UpdateQuestionSubjectAction;
use App\Enums\BooleanEnum;
use App\Helpers\PowerGridHelper;
use App\Models\QuestionSubject;
use App\Traits\HasModalForm;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class QuestionSubjectTable extends PowerGridComponent
{
    use HasModalForm;
    use PowerGridHelperTrait;
    use Toast;
    public string $tableName     = 'index_questionSubject_datatable';
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
                'name'        => 'title',
                'type'        => 'input',
                'label'       => trans('validation.attributes.title'),
                'placeholder' => trans('validation.attributes.title'),
                'required'    => true,
            ],
            [
                'name'        => 'description',
                'type'        => 'input',
                'label'       => trans('validation.attributes.description'),
                'placeholder' => trans('validation.attributes.description'),
                'required'    => true,
            ],
            [
                'name'    => 'published',
                'type'    => 'toggle',
                'label'   => trans('datatable.status'),
                'default' => false,
            ],
        ];
    }

    protected function getModelClass(): string
    {
        return QuestionSubject::class;
    }

    protected function getStoreActionClass(): string
    {
        return StoreQuestionSubjectAction::class;
    }

    protected function getUpdateActionClass(): string
    {
        return UpdateQuestionSubjectAction::class;
    }

    protected function getModelTranslationKey(): string
    {
        return 'questionSubject.model';
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('questionSubject.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['action' => 'openCreateModal', 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('questionSubject.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return QuestionSubject::query();
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
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
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

    public function actions(QuestionSubject $row): array
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
            'link' => route('admin.question-subject.create'),
        ]);
    }
}
