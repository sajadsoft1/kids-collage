<?php

declare(strict_types=1);

namespace App\Actions\CourseSession;

use App\Models\CourseSession;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseSessionAction
{
    use AsAction;

    public function __construct() {}

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
     * @throws Throwable
     */
    public function handle(CourseSession $courseSession, array $payload): CourseSession
    {
        return DB::transaction(function () use ($courseSession, $payload) {
            $courseSession->update($payload);

            return $courseSession->refresh();
        });
    }
}
