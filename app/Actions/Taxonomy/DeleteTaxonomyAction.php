<?php

declare(strict_types=1);

namespace App\Actions\Taxonomy;

use App\Models\Notebook;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTaxonomyAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Notebook $notebook): bool
    {
        return DB::transaction(function () use ($notebook) {
            return $notebook->delete();
        });
    }
}
