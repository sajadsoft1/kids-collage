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
     *     category_id:int
     * }               $payload
     * @throws Throwable
     */
    public function handle(QuestionSubject $questionSubject, array $payload): QuestionSubject
    {
        return DB::transaction(function () use ($questionSubject, $payload) {
            $questionSubject->update([
                'ordering'    => Arr::get($payload, 'ordering', $questionSubject->ordering),
                'category_id' => Arr::get($payload, 'category_id', $questionSubject->category_id),
            ]);
            $this->syncTranslationAction->handle($questionSubject, Arr::only($payload, ['title', 'description']));

            return $questionSubject->refresh();
        });
    }
}
