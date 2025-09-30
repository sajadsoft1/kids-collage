<?php

namespace App\Actions\CourseSession;

use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\CourseSession;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseSessionAction
{
    use AsAction;

    public function __construct()
    {
    }

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
     *     session_type:string,
     * } $payload
     * @return CourseSession
     * @throws Throwable
     */
    public function handle(array $payload): CourseSession
    {
        return DB::transaction(function () use ($payload) {
            $model = CourseSession::create([
                'course_id'                  => $payload['course_id'],
                'course_session_template_id' => $payload['course_session_template_id'],
                'date'                       => $payload['date'] ?? null,
                'start_time'                 => $payload['start_time'] ?? null,
                'end_time'                   => $payload['end_time'] ?? null,
                'room_id'                    => $payload['room_id'] ?? null,
                'meeting_link'               => $payload['meeting_link'] ?? null,
                'recording_link'             => $payload['recording_link'] ?? null,
                'status'                     => $payload['status'] ?? SessionStatus::PLANNED->value,
                'session_type'               => $payload['session_type'] ?? SessionType::IN_PERSON->value,
            ]);
            return $model->refresh();
        });
    }
}
