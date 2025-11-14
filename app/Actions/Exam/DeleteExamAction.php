<?php

declare(strict_types=1);

namespace App\Actions\Exam;

use App\Models\Exam;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteExamAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Exam $exam): bool
    {
        return DB::transaction(function () use ($exam) {
            return $exam->delete();
        });
    }
}
