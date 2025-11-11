<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionCompetency;

use App\Actions\QuestionCompetency\StoreQuestionCompetencyAction;
use App\Actions\QuestionCompetency\UpdateQuestionCompetencyAction;
use App\Helpers\PowerGridHelper;
use App\Models\QuestionCompetency;
use App\Traits\HasModalForm;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class QuestionCompetencyTable extends PowerGridComponent
{
    use HasModalForm;
    use PowerGridHelperTrait;
    use Toast;

    public string $tableName     = 'index_questionCompetency_datatable';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title', 'actions'];
    }

    protected function afterPowerGridSetUp(array &$setup): void
    {
        $setup[1]->includeViewOnBottom('components.admin.shared.modal-form');
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
        ];
    }

    protected function getModelClass(): string
    {
        return QuestionCompetency::class;
    }

    protected function getStoreActionClass(): string
    {
        return StoreQuestionCompetencyAction::class;
    }

    protected function getUpdateActionClass(): string
    {
        return UpdateQuestionCompetencyAction::class;
    }

    protected function getModelTranslationKey(): string
    {
        return 'questionCompetency.model';
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('questionCompetency.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['action' => 'openCreateModal', 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('questionCompetency.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return QuestionCompetency::query();
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
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
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

    public function actions(QuestionCompetency $row): array
    {
        return [
            PowerGridHelper::btnTranslate($row),
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
            'link' => route('admin.question-competency.create'),
        ]);
    }
}
