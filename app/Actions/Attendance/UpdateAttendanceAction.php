<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Attendance;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateAttendanceAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(Attendance $attendance, array $payload): Attendance
    {
        return DB::transaction(function () use ($attendance, $payload) {
            $attendance->update($payload);
            $this->syncTranslationAction->handle($attendance, Arr::only($payload, ['title', 'description']));

            return $attendance->refresh();
        });
    }
}
