<?php

namespace App\Actions\Enrollment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Enrollment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateEnrollmentAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Enrollment $enrollment
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Enrollment
     * @throws Throwable
     */
    public function handle(Enrollment $enrollment, array $payload): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $payload) {
            $enrollment->update($payload);
            $this->syncTranslationAction->handle($enrollment, Arr::only($payload, ['title', 'description']));

            return $enrollment->refresh();
        });
    }
}
