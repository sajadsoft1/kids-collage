<?php

declare(strict_types=1);

namespace App\Actions\CourseTemplate;

use App\Models\CourseTemplate;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCourseTemplateAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(CourseTemplate $courseTemplate): bool
    {
        return DB::transaction(function () use ($courseTemplate) {
            abort_if($courseTemplate->courses()->exists(), 403, trans('courseTemplate.exceptions.not_allowed_to_delete_course_template_due_to_courses'));

            $courseTemplate->sessionTemplates()->delete();

            return $courseTemplate->delete();
        });
    }
}
