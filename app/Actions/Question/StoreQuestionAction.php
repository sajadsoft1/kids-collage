<?php

declare(strict_types=1);

namespace App\Actions\Question;

use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreQuestionAction
{
    use AsAction;

    /**
     * @param array{
     *     type: string,
     *     title: string,
     *     body?: string|null,
     *     explanation?: string|null,
     *     default_score?: float|int,
     *     config?: array,
     *     correct_answer?: array,
     *     created_by?: int,
     *     is_active?: bool,
     *     is_public?: bool,
     *     is_survey_question?: bool,
     *     category_id?: int|null,
     *     subject_id?: int|null,
     *     competency_id?: int|null,
     *     difficulty?: string|null,
     *     metadata?: array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Question
    {
        return DB::transaction(function () use ($payload) {
            // Question model doesn't use translation system
            // All fields (including title) are stored directly in questions table
            $model = Question::create($payload);

            return $model->refresh();
        });
    }
}
