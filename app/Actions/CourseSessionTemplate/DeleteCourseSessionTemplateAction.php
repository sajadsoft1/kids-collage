<?php

declare(strict_types=1);

namespace App\Actions\CourseSessionTemplate;

use App\Models\CourseSessionTemplate;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCourseSessionTemplateAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(CourseSessionTemplate $courseSessionTemplate): bool
    {
        return DB::transaction(function () use ($courseSessionTemplate) {
            return $courseSessionTemplate->delete();
        });
    }
}
