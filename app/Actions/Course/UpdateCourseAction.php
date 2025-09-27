<?php

declare(strict_types=1);

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
     * @param array{
     *     title:string,
     *     description:string,
     *     body:string,
     *     published:bool,
     *     published_at:string,
     *     teacher_id:int,
     *     category_id:int,
     *     price:float,
     *     type:string,
     *     start_date:string,
     *     end_date:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(Course $course, array $payload): Course
    {
        return DB::transaction(function () use ($course, $payload) {
            $course->update(Arr::only($payload, [
                'published', 'published_at', 'teacher_id', 'category_id',
                'price', 'type', 'start_date', 'end_date',
            ]));
            $this->syncTranslationAction->handle($course, Arr::only($payload, ['title', 'description', 'body']));

            return $course->refresh();
        });
    }
}
