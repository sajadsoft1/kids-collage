<?php

declare(strict_types=1);

namespace App\Actions\Survey;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Exam;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSurveyAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     type:string,
     *     rules?: array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Exam
    {
        return DB::transaction(function () use ($payload) {
            $rules = $payload['rules'] ?? null;
            unset($payload['rules']);

            $model = Exam::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            // Set rules if provided
            if ($rules !== null) {
                $model->setRules($rules);
                $model->save();
            }

            return $model->refresh();
        });
    }
}
