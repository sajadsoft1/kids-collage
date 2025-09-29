<?php

namespace App\Actions\Session;

use App\Models\CourseSession;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteSessionAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(CourseSession $session): bool
    {
        return DB::transaction(function () use ($session) {
            return $session->delete();
        });
    }
}
