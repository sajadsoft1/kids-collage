<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Models\Attendance;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreAttendanceAction
{
    use AsAction;

    /**
     * @param array{
     *     enrollment_id:int,
     *     course_session_id?:int,
     *     session_id?:int,
     *     present:bool,
     *     arrival_time:DateTimeInterface|string|null,
     *     leave_time:DateTimeInterface|string|null,
     *     excuse_note?:string|null
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Attendance
    {
        return DB::transaction(function () use ($payload) {
            // Support both session_id (legacy) and course_session_id
            if (isset($payload['session_id']) && ! isset($payload['course_session_id'])) {
                $payload['course_session_id'] = $payload['session_id'];
                unset($payload['session_id']);
            }

            $model = Attendance::create($payload);

            return $model->refresh();
        });
    }
}
