<?php

declare(strict_types=1);

namespace App\Actions\QuestionOption;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionOption;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreQuestionOptionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): QuestionOption
    {
        return DB::transaction(function () use ($payload) {
            $model = QuestionOption::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
