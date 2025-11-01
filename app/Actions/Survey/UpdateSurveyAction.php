<?php

declare(strict_types=1);

namespace App\Actions\Survey;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Exam;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSurveyAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     rules?: array
     * }               $payload
     * @throws Throwable
     */
    public function handle(Exam $exam, array $payload): Exam
    {
        return DB::transaction(function () use ($exam, $payload) {
            $rules = $payload['rules'] ?? null;
            unset($payload['rules']);

            $exam->update($payload);
            $this->syncTranslationAction->handle($exam, Arr::only($payload, ['title', 'description']));

            // Update rules if provided
            if ($rules !== null) {
                $exam->setRules($rules);
                $exam->save();
            }

            return $exam->refresh();
        });
    }
}
