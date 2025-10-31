<?php

declare(strict_types=1);

namespace App\Actions\QuestionSubject;

use App\Models\QuestionSubject;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteQuestionSubjectAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(QuestionSubject $questionSubject): bool
    {
        return DB::transaction(function () use ($questionSubject) {
            return $questionSubject->delete();
        });
    }
}
