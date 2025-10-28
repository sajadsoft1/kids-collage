<?php

namespace App\Actions\QuestionSubject;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionSubject;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateQuestionSubjectAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param QuestionSubject $questionSubject
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return QuestionSubject
     * @throws Throwable
     */
    public function handle(QuestionSubject $questionSubject, array $payload): QuestionSubject
    {
        return DB::transaction(function () use ($questionSubject, $payload) {
            $questionSubject->update($payload);
            $this->syncTranslationAction->handle($questionSubject, Arr::only($payload, ['title', 'description']));

            return $questionSubject->refresh();
        });
    }
}
