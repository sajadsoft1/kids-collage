<?php

declare(strict_types=1);

namespace App\Actions\Branch;

use App\Models\Branch;
use Exception;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteBranchAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Branch $branch): bool
    {
        return DB::transaction(function () use ($branch) {
            // Prevent deletion of default branch
            if ($branch->is_default) {
                throw new Exception('Cannot delete the default branch.');
            }

            return $branch->delete();
        });
    }
}
