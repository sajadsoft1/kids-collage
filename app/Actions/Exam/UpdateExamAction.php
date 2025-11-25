<?php

declare(strict_types=1);

namespace App\Actions\Exam;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Exam;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateExamAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title?:string,
     *     description?:string|null,
     *     category_id?:int|null,
     *     type?:string,
     *     total_score?:float|null,
     *     duration?:int|null,
     *     pass_score?:float|null,
     *     max_attempts?:int|null,
     *     shuffle_questions?:bool,
     *     show_results?:string,
     *     allow_review?:bool,
     *     starts_at?:\Carbon\CarbonInterface|string|null,
     *     ends_at?:\Carbon\CarbonInterface|string|null,
     *     status?:string,
     *     rules?: array|null,
     *     tags?: array<int, string>|null
     * } $payload
     * @throws Throwable
     */
    public function handle(Exam $exam, array $payload): Exam
    {
        return DB::transaction(function () use ($exam, $payload) {
            $rules = $payload['rules'] ?? null;
            $tags = $payload['tags'] ?? null;
            unset($payload['rules'], $payload['tags']);

            $exam->update($payload);
            $this->syncTranslationAction->handle($exam, Arr::only($payload, ['title', 'description']));

            // Update rules if provided
            if ($rules !== null) {
                $exam->setRules($rules);
                $exam->save();
            }

            if (is_array($tags)) {
                $exam->syncTags($tags);
            }

            return $exam->refresh();
        });
    }
}
