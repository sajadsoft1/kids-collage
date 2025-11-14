<?php

declare(strict_types=1);

namespace App\Actions\QuestionCompetency;

use App\Models\QuestionCompetency;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteQuestionCompetencyAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(QuestionCompetency $questionCompetency): bool
    {
        return DB::transaction(function () use ($questionCompetency) {
            return $questionCompetency->delete();
        });
    }
}
