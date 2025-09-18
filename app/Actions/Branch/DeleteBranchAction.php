<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteBranchAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Branch $branch): bool
    {
        return DB::transaction(function () use ($branch) {
            return $branch->delete();
        });
    }
}
