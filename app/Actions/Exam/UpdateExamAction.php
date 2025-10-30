<?php

declare(strict_types=1);

namespace App\Actions\Exam;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Exam;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateExamAction
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
    public function handle(Exam $exam, array $payload): Exam
    {
        return DB::transaction(function () use ($exam, $payload) {
            $exam->update($payload);
            $this->syncTranslationAction->handle($exam, Arr::only($payload, ['title', 'description']));

            return $exam->refresh();
        });
    }
}
