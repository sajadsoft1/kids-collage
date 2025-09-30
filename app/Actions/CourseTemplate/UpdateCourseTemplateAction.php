<?php

namespace App\Actions\CourseTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param CourseTemplate $courseTemplate
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return CourseTemplate
     * @throws Throwable
     */
    public function handle(CourseTemplate $courseTemplate, array $payload): CourseTemplate
    {
        return DB::transaction(function () use ($courseTemplate, $payload) {
            $courseTemplate->update($payload);
            $this->syncTranslationAction->handle($courseTemplate, Arr::only($payload, ['title', 'description']));

            return $courseTemplate->refresh();
        });
    }
}
