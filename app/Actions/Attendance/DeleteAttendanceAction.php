<?php

namespace App\Actions\Attendance;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteAttendanceAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Attendance $attendance): bool
    {
        return DB::transaction(function () use ($attendance) {
            return $attendance->delete();
        });
    }
}
