<?php

declare(strict_types=1);

namespace App\Actions\Enrollment;

use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteEnrollmentAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Enrollment $enrollment): bool
    {
        return DB::transaction(function () use ($enrollment) {
            return $enrollment->delete();
        });
    }
}
