<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Resource;

use App\Enums\ResourceType;
use App\Helpers\PowerGridHelper;
use App\Models\Resource;
use App\Traits\HasLearningModal;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * Resource index: list of resources (files, links, etc.) with type, visibility, order, and attachment to session templates.
 */
final class ResourceTable extends PowerGridComponent
{
    use HasLearningModal;
    use PowerGridHelperTrait;

    public string $tableName = 'index_resource_datatable';

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
            ['label' => trans('general.page.index.title', ['model' => trans('resource.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return $this->withLearningModalActions([
            ['link' => route('admin.resource.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('resource.model')])],
        ]);
    }

    /**
     * Learning modal sections for this page.
     *
     * @return array<int|string, array{title: string, content: string, icon?: string}>
     */
    public function getLearningSections(): array
    {
        return [
            'index' => [
                'title' => trans('resource.learning.index.title'),
                'content' => trans('resource.learning.index.content'),
                'icon' => 'o-cube',
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Resource::query();
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
            ->add('type_formated', fn ($row) => $row->type->title())
            ->add('attached_to', fn ($row) => trans('resource.page.sessions_count', ['count' => $row->courseSessionTemplates->count()]))
            ->add('is_public_formatted', fn ($row) => $row->is_public ? trans('resource.page.public') : trans('resource.page.private'))
            ->add('order', fn ($row) => $row->order)
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('resource.type'), 'type_formated', 'type')
                ->sortable()
                ->searchable(),
            Column::make(trans('resource.attached_to'), 'attached_to'),
            Column::make(trans('resource.page.visibility'), 'is_public_formatted')
                ->sortable(),
            Column::make(trans('resource.order'), 'order')
                ->sortable(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::select('type', 'type')
            //     ->dataSource(ResourceType::options())
            //     ->optionValue('value')
            //     ->optionLabel('label'),

            Filter::select('is_public', 'is_public')
                ->dataSource([
                    ['value' => '1', 'label' => trans('resource.page.public')],
                    ['value' => '0', 'label' => trans('resource.page.private')],
                ])
                ->optionValue('value')
                ->optionLabel('label'),
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(Resource $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.resource.create'),
        ]);
    }
}
