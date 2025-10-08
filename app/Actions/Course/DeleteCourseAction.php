<?php

declare(strict_types=1);

namespace App\Actions\Course;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCourseAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Course $course): bool
    {
        return DB::transaction(function () use ($course) {
            return $course->delete();
        });
    }
}
