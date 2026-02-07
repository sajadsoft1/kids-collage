<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletesTrait;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

trait PowerGridHelperTrait
{
    public array $fixedColumns = ['title', 'actions'];
    public array $persistItems = ['columns', 'filters', 'sort'];

    public function setUp(): array
    {
        $this->beforePowerGridSetUp();
        // The persist feature saves the state of columns, sorting, and filters so they can be reused in the future when the Table is loaded again.
        $this->persist(
            tableItems: $this->persistItems,
            prefix: 'user_' . (auth()->id() ?? '0')
        );

        $header = PowerGrid::header()
            ->includeViewOnTop('components.admin.shared.bread-crumbs')
            ->showToggleColumns()
            ->showSearchInput();

        if ($this->powerGridModelUsesSoftDeletes()) {
            $header = $header->showSoftDeletes(showMessage: true);
        }

        $setup = [
            $header,

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        $this->afterPowerGridSetUp($setup);

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns(...$this->fixedColumns);
        }

        return $setup;
    }

    /** Whether the table datasource model uses Laravel SoftDeletes. */
    protected function powerGridModelUsesSoftDeletes(): bool
    {
        $datasource = $this->datasource();
        if ( ! $datasource instanceof EloquentBuilder) {
            return false;
        }
        $modelClass = $datasource->getModel();

        return in_array(SoftDeletesTrait::class, class_uses_recursive($modelClass) ?: [], true);
    }

    /**
     * Actions to show when the row is soft-deleted (e.g. restore only).
     * Use in actions(): return $this->getSoftDeleteRowActions($row) ?: [ ... normal actions ... ];
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Button>
     */
    public function getSoftDeleteRowActions(mixed $row): array
    {
        if ( ! $this->powerGridModelUsesSoftDeletes() || ! method_exists($row, 'trashed') || ! $row->trashed()) {
            return [];
        }

        return [\App\Helpers\PowerGridHelper::btnRestore($row)];
    }

    /** Restore a soft-deleted row. Call from tables whose model uses SoftDeletes. */
    public function restoreRow(array|int|string $rowId): void
    {
        $id = is_array($rowId) ? (int) ($rowId[0] ?? 0) : (int) $rowId;
        if ($id <= 0) {
            return;
        }
        if ( ! $this->powerGridModelUsesSoftDeletes()) {
            return;
        }
        $modelClass = $this->datasource()->getModel();
        $model = $modelClass->withTrashed()->find($id);
        if ($model === null) {
            return;
        }
        $model->restore();
        $modelKey = Str::kebab(class_basename($modelClass));
        $message = trans('general.model_has_restored_successfully', [
            'model' => trans("{$modelKey}.model"),
        ]);
        if (method_exists($this, 'success')) {
            $this->success($message);
        } else {
            session()->flash('success', $message);
        }
    }

    protected function beforePowerGridSetUp(): void {}

    protected function afterPowerGridSetUp(array &$setup): void {}
    //    public function rendering(): void
    //    {
    //        $this->js("
    //            document.querySelectorAll('[data-column=\"actions\"]').forEach(element => {element.removeAttribute('style')});
    //            document.querySelectorAll('.powergrid-id').forEach(element => element.style.width = 0);
    //            console.log('rendering');
    //        ");
    //    }

    #[On('toggle')]
    public function toggle($rowId, $toogleField): void
    {
        $model = $this->datasource()->getModel()::where('id', $rowId)->first();
        $model->update([$toogleField => ! $model->{$toogleField}->value]);
    }

    #[On('force-delete')]
    public function forceDelete($rowId): void
    {
        $modelClass = StringHelper::basename($this->datasource()->getModel()::class);
        $action = resolve('\\App\\Actions\\' . $modelClass . '\\Delete' . $modelClass . 'Action');
        $model = $this->datasource()->getModel()::where('id', $rowId)->first();
        $action::run($model);
    }

    #[On('delete')]
    public function delete($rowId): void
    {
        $this->js('Swal.fire({
          title: "' . trans('powergrid.modal.delete.title') . '",
          text: "' . trans('powergrid.modal.delete.body') . '",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "' . trans('powergrid.modal.delete.footer.delete') . '",
          cancelButtonText: "' . trans('powergrid.modal.delete.footer.cancel') . '",
         }).then((result) => {
         if (result.isConfirmed) {
         Livewire.dispatch("force-delete", { rowId: ' . $rowId . ' });
         }
         })');
    }

    protected function queryString(): array
    {
        $paginationQueryString = $this->queryStringHandlesPagination();

        $paginationQueryString['paginators.page'] ??= ['as' => 'page', 'history' => true, 'keep' => false];
        $paginationQueryString['paginators.page']['except'] = 1;

        return [
            ...$paginationQueryString,
            'search' => ['except' => ''],
            ...$this->powerGridQueryString(),
        ];
    }
}
