<?php

declare(strict_types=1);

namespace App\Actions\QuestionSystem;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionSystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateQuestionSystemAction
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
     * }               $payload
     * @throws Throwable
     */
    public function handle(QuestionSystem $questionSystem, array $payload): QuestionSystem
    {
        return DB::transaction(function () use ($questionSystem, $payload) {
            $questionSystem->update($payload);
            $this->syncTranslationAction->handle($questionSystem, Arr::only($payload, ['title', 'description']));

            return $questionSystem->refresh();
        });
    }
}
