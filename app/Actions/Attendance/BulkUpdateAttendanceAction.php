<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Enums\BooleanEnum;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class BulkUpdateAttendanceAction
{
    use AsAction;

    /**
     * Bulk update attendance records: set present/absent and optional excuse.
     *
     * @param  array<int> $attendanceIds
     * @throws Throwable
     */
    public function handle(array $attendanceIds, bool $present, ?string $excuseNote = null): int
    {
        if (empty($attendanceIds)) {
            return 0;
        }

        $payload = [
            'present' => $present ? BooleanEnum::ENABLE : BooleanEnum::DISABLE,
            'excuse_note' => $excuseNote,
        ];

        if ($present) {
            $payload['arrival_time'] = now();
            $payload['leave_time'] = null;
        } else {
            $payload['arrival_time'] = null;
            $payload['leave_time'] = null;
        }

        return (int) DB::transaction(function () use ($attendanceIds, $payload) {
            return Attendance::query()
                ->whereIn('id', $attendanceIds)
                ->update($payload);
        });
    }
}
