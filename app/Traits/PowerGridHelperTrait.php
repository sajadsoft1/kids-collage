<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\StringHelper;
use Livewire\Attributes\On;

trait PowerGridHelperTrait
{
    //    public function rendering(): void
    //    {
    //        $this->js("
    //            document.querySelectorAll('[data-column=\"actions\"]').forEach(element => {element.removeAttribute('style')});
    //            document.querySelectorAll('.powergrid-id').forEach(element => element.style.width = 0);
    //            console.log('rendering');
    //        ");
    //    }

    #[On('toggle')]
    public function toggle($rowId): void
    {
        $model = $this->datasource()->getModel()::where('id', $rowId)->first();
        $model->update(['published' => ! $model->published->value]);
    }

    #[On('force-delete')]
    public function forceDelete($rowId): void
    {
        $modelClass = StringHelper::basename($this->datasource()->getModel()::class);
        $action     = resolve('\\App\\Actions\\' . $modelClass . '\\Delete' . $modelClass . 'Action');
        $model      = $this->datasource()->getModel()::where('id', $rowId)->first();
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
}
