<?php

declare(strict_types=1);

namespace App\Actions\CourseTemplateLevel;

use App\Models\CourseTemplateLevel;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCourseTemplateLevelAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(CourseTemplateLevel $courseTemplateLevel): bool
    {
        return DB::transaction(function () use ($courseTemplateLevel) {
            return $courseTemplateLevel->delete();
        });
    }
}
