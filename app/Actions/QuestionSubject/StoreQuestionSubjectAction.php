<?php

declare(strict_types=1);

namespace App\Actions\QuestionSubject;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionSubject;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreQuestionSubjectAction
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
    public function handle(array $payload): QuestionSubject
    {
        return DB::transaction(function () use ($payload) {
            $model =  QuestionSubject::create([
                'ordering' => QuestionSubject::max('ordering') + 1,
                'category_id' => Arr::get($payload, 'category_id'),
            ]);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
