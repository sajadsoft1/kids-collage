<?php

namespace App\Actions\Notebook;

use App\Models\Notebook;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteNotebookAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Notebook $notebook): bool
    {
        return DB::transaction(function () use ($notebook) {
            return $notebook->delete();
        });
    }
}
