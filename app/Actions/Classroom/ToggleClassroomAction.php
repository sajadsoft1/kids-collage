<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleClassroomAction
{
    use AsAction;

    public function __construct(private readonly ClassroomRepositoryInterface $repository)
    {
    }

    public function handle(Classroom $classroom): Classroom
    {
        return DB::transaction(function () use ($classroom) {
            /** @var Classroom $model */
            $model =  $this->repository->toggle($classroom);

            return $model->refresh();
        });
    }
}
