<?php

namespace App\Actions\Attendance;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Attendance;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreAttendanceAction
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
     * @return Attendance
     * @throws Throwable
     */
    public function handle(array $payload): Attendance
    {
        return DB::transaction(function () use ($payload) {
            $model =  Attendance::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
