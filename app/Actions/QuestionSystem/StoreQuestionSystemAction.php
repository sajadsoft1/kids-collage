<?php

declare(strict_types=1);

namespace App\Actions\QuestionSystem;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionSystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreQuestionSystemAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     category_id:int,
     *     ordering:int
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): QuestionSystem
    {
        return DB::transaction(function () use ($payload) {
            $model = QuestionSystem::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
