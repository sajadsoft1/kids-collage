<?php

namespace App\Actions\CourseSession;

use App\Models\CourseSession;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCourseSessionAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(CourseSession $courseSession): bool
    {
        return DB::transaction(function () use ($courseSession) {
            return $courseSession->delete();
        });
    }
}
