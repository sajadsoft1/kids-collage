<?php

declare(strict_types=1);

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
     * @param array{
     *     title:string,
     *     description:string,
     *     ordering:int,
     * }               $payload
     * @throws Throwable
     */
    public function handle(QuestionSubject $questionSubject, array $payload): QuestionSubject
    {
        return DB::transaction(function () use ($questionSubject, $payload) {
            $questionSubject->update([
                'ordering' => Arr::get($payload, 'ordering', $questionSubject->ordering),
            ]);
            $this->syncTranslationAction->handle($questionSubject, Arr::only($payload, ['title', 'description']));

            return $questionSubject->refresh();
        });
    }
}
