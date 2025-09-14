<?php

namespace App\Actions\Opinion;

use App\Models\Opinion;
use App\Repositories\Opinion\OpinionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleOpinionAction
{
    use AsAction;

    public function __construct(private readonly OpinionRepositoryInterface $repository)
    {
    }

    public function handle(Opinion $opinion): Opinion
    {
        return DB::transaction(function () use ($opinion) {
            /** @var Opinion $model */
            $model =  $this->repository->toggle($opinion);

            return $model->refresh();
        });
    }
}
