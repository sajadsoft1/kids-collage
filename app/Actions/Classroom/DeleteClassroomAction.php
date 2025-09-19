<?php

namespace App\Actions\Classroom;

use App\Models\Classroom;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteClassroomAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Classroom $classroom): bool
    {
        return DB::transaction(function () use ($classroom) {
            return $classroom->delete();
        });
    }
}
