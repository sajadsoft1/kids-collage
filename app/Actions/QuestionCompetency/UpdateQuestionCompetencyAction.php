<?php

declare(strict_types=1);

namespace App\Actions\QuestionCompetency;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\QuestionCompetency;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateQuestionCompetencyAction
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
     * }               $payload
     * @throws Throwable
     */
    public function handle(QuestionCompetency $questionCompetency, array $payload): QuestionCompetency
    {
        return DB::transaction(function () use ($questionCompetency, $payload) {
            $questionCompetency->update([
                'ordering' => Arr::get($payload, 'ordering', $questionCompetency->ordering),
            ]);
            $this->syncTranslationAction->handle($questionCompetency, Arr::only($payload, ['title', 'description']));

            return $questionCompetency->refresh();
        });
    }
}
