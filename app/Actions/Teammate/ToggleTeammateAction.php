<?php

namespace App\Actions\Teammate;

use App\Models\Teammate;
use App\Repositories\Teammate\TeammateRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleTeammateAction
{
    use AsAction;

    public function __construct(private readonly TeammateRepositoryInterface $repository)
    {
    }

    public function handle(Teammate $teammate): Teammate
    {
        return DB::transaction(function () use ($teammate) {
            /** @var Teammate $model */
            $model =  $this->repository->toggle($teammate);

            return $model->refresh();
        });
    }
}
