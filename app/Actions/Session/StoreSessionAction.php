<?php

declare(strict_types=1);

namespace App\Actions\Session;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseSession;
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
     *     course_id:int,
     *     course_session_template_id:int,
     *     date:string|null,
     *     start_time:string|null,
     *     end_time:string|null,
     *     room_id:int|null,
     *     meeting_link:string|null,
     *     recording_link:string|null,
     *     status:string,
     *     session_type:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): CourseSession
    {
        return DB::transaction(function () use ($payload) {
            $model = CourseSession::create(Arr::only($payload, [
                'course_id',
                'course_session_template_id',
                'date',
                'start_time',
                'end_time',
                'room_id',
                'meeting_link',
                'recording_link',
                'status',
                'session_type',
            ]));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));

            return $model->refresh();
        });
    }
}
