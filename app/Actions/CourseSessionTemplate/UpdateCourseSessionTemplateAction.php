<?php

namespace App\Actions\CourseSessionTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseSessionTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseSessionTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param CourseSessionTemplate $courseSessionTemplate
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return CourseSessionTemplate
     * @throws Throwable
     */
    public function handle(CourseSessionTemplate $courseSessionTemplate, array $payload): CourseSessionTemplate
    {
        return DB::transaction(function () use ($courseSessionTemplate, $payload) {
            $courseSessionTemplate->update($payload);
            $this->syncTranslationAction->handle($courseSessionTemplate, Arr::only($payload, ['title', 'description']));

            return $courseSessionTemplate->refresh();
        });
    }
}
