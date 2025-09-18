<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use App\Repositories\Branch\BranchRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleBranchAction
{
    use AsAction;

    public function __construct(private readonly BranchRepositoryInterface $repository)
    {
    }

    public function handle(Branch $branch): Branch
    {
        return DB::transaction(function () use ($branch) {
            /** @var Branch $model */
            $model =  $this->repository->toggle($branch);

            return $model->refresh();
        });
    }
}
