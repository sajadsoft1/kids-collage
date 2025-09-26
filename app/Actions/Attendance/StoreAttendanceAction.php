<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreAttendanceAction
{
    use AsAction;

    /**
     * @param array{
     *     enrollment_id:int,
     *     session_id:int,
     *     present:bool,
     *     arrival_time:string|null,
     *     leave_time:string|null
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Attendance
    {
        return DB::transaction(function () use ($payload) {
            $model = Attendance::create($payload);

            return $model->refresh();
        });
    }
}
