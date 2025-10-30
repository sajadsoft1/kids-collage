<?php

declare(strict_types=1);

namespace App\Actions\Question;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateQuestionAction
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
    public function handle(Question $question, array $payload): Question
    {
        return DB::transaction(function () use ($question, $payload) {
            $question->update($payload);
            $this->syncTranslationAction->handle($question, Arr::only($payload, ['title', 'description']));

            return $question->refresh();
        });
    }
}
