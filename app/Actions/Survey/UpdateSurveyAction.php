<?php

declare(strict_types=1);

namespace App\Actions\Survey;

use App\Models\Exam;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSurveyAction
{
    use AsAction;

    /**
     * @param array{
     *     title?: string,
     *     description?: string,
     *     starts_at?: string|null,
     *     ends_at?: string|null,
     *     status?: string,
     *     rules?: array
     * } $payload
     * @throws Throwable
     */
    public function handle(Exam $exam, array $payload): Exam
    {
        return DB::transaction(function () use ($exam, $payload) {
            $rules = $payload['rules'] ?? null;
            unset($payload['rules']);

            // Exam model doesn't use translation system
            // All fields (including title and description) are stored directly in exams table
            $exam->update($payload);

            // Update rules if provided
            if ($rules !== null) {
                $exam->setRules($rules);
                $exam->save();
            }

            return $exam->refresh();
        });
    }
}
