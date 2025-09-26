<?php

namespace App\Actions\Course;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Course;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Course $course
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Course
     * @throws Throwable
     */
    public function handle(Course $course, array $payload): Course
    {
        return DB::transaction(function () use ($course, $payload) {
            $course->update($payload);
            $this->syncTranslationAction->handle($course, Arr::only($payload, ['title', 'description']));

            return $course->refresh();
        });
    }
}
