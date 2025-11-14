<?php

declare(strict_types=1);

namespace App\Actions\QuestionOption;

use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteQuestionOptionAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(QuestionOption $questionOption): bool
    {
        return DB::transaction(function () use ($questionOption) {
            return $questionOption->delete();
        });
    }
}
