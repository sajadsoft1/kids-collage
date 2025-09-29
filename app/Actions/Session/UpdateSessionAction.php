<?php

namespace App\Actions\Session;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSessionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param CourseSession $session
     * @param array{
     *     date:string|null,
     *     start_time:string|null,
     *     end_time:string|null,
     *     room_id:int|null,
     *     meeting_link:string|null,
     *     recording_link:string|null,
     *     status:string,
     *     session_type:string
     * } $payload
     * @return CourseSession
     * @throws Throwable
     */
    public function handle(CourseSession $session, array $payload): CourseSession
    {
        return DB::transaction(function () use ($session, $payload) {
            $session->update(Arr::only($payload, [
                'date',
                'start_time',
                'end_time',
                'room_id',
                'meeting_link',
                'recording_link',
                'status',
                'session_type',
            ]));
            $this->syncTranslationAction->handle($session, Arr::only($payload, ['title', 'description']));

            return $session->refresh();
        });
    }
}
