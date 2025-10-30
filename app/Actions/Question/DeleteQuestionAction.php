<?php

declare(strict_types=1);

namespace App\Actions\Question;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteQuestionAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Question $question): bool
    {
        return DB::transaction(function () use ($question) {
            return $question->delete();
        });
    }
}
