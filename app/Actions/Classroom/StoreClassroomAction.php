<?php

namespace App\Actions\Classroom;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Classroom;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreClassroomAction
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
     * @return Classroom
     * @throws Throwable
     */
    public function handle(array $payload): Classroom
    {
        return DB::transaction(function () use ($payload) {
            $model =  Classroom::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
