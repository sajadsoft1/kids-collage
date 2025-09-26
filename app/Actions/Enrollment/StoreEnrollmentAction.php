<?php

namespace App\Actions\Enrollment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Enrollment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreEnrollmentAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @return Enrollment
     * @throws Throwable
     */
    public function handle(array $payload): Enrollment
    {
        return DB::transaction(function () use ($payload) {
            $model =  Enrollment::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
