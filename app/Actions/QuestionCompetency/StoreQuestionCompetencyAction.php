<?php

declare(strict_types=1);

namespace App\Actions\QuestionCompetency;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionCompetency;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreQuestionCompetencyAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     ordering:int
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): QuestionCompetency
    {
        return DB::transaction(function () use ($payload) {
            $model =  QuestionCompetency::create([
                'ordering' => QuestionCompetency::max('ordering') + 1,
            ]);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
