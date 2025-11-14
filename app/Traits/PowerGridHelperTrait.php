<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\StringHelper;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

trait PowerGridHelperTrait
{
    public array $fixedColumns = ['title', 'actions'];

    public function setUp(): array
    {
        $this->beforePowerGridSetUp();
        // The persist feature saves the state of columns, sorting, and filters so they can be reused in the future when the Table is loaded again.
        $this->persist(
            tableItems: ['columns', 'filters', 'sort'],
            prefix: 'user_' . auth()->id() ?? ''
        );
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showToggleColumns()
                ->showSearchInput(),

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
