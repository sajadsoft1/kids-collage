<?php

declare(strict_types=1);

namespace App\Actions\Session;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSessionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     body:string,
     *     course_id:int,
     *     teacher_id:int,
     *     start_time:string,
     *     end_time:string,
     *     room_id:int|null,
     *     meeting_link:string|null,
     *     session_number:int,
     *     languages:array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Session
    {
        return DB::transaction(function () use ($payload) {
            $model = Session::create(Arr::only($payload, ['course_id', 'teacher_id', 'start_time', 'end_time', 'room_id', 'meeting_link', 'session_number', 'languages']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));

            return $model->refresh();
        });
    }
}
