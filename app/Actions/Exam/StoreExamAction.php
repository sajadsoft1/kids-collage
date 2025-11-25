<?php

declare(strict_types=1);

namespace App\Actions\Exam;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Exam;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreExamAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description?:string|null,
     *     category_id?:int|null,
     *     type:string,
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
     *     created_by?:int|null,
     *     rules?: array|null,
     *     tags?: array<int, string>|null
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Exam
    {
        return DB::transaction(function () use ($payload) {
            $rules = $payload['rules'] ?? null;
            $tags = $payload['tags'] ?? null;
            unset($payload['rules'], $payload['tags']);

            $payload['created_by'] ??= Auth::id();

            $model = Exam::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            // Set rules if provided
            if ($rules !== null) {
                $model->setRules($rules);
                $model->save();
            }

            if (is_array($tags)) {
                $model->syncTags($tags);
            }

            return $model->refresh();
        });
    }
}
