<?php

namespace App\Actions\CourseTemplate;

use App\Models\CourseTemplate;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCourseTemplateAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(CourseTemplate $courseTemplate): bool
    {
        return DB::transaction(function () use ($courseTemplate) {
            return $courseTemplate->delete();
        });
    }
}
