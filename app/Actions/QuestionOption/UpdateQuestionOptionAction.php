<?php

declare(strict_types=1);

namespace App\Actions\QuestionOption;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionOption;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateQuestionOptionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(QuestionOption $questionOption, array $payload): QuestionOption
    {
        return DB::transaction(function () use ($questionOption, $payload) {
            $questionOption->update($payload);
            $this->syncTranslationAction->handle($questionOption, Arr::only($payload, ['title', 'description']));

            return $questionOption->refresh();
        });
    }
}
