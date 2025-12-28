<?php

declare(strict_types=1);

namespace App\Actions\QuestionSystem;

use App\Models\QuestionSystem;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteQuestionSystemAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(QuestionSystem $questionSystem): bool
    {
        return DB::transaction(function () use ($questionSystem) {
            return $questionSystem->delete();
        });
    }
}
