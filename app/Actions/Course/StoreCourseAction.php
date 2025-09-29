<?php

declare(strict_types=1);

namespace App\Actions\Course;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Course;
use App\Models\CourseTemplate;
use App\Models\Term;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseAction
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
     *     slug:string,
     *     published:bool,
     *     published_at:string,
     *     user_id:int,
     *     teacher_id:int,
     *     category_id:int,
     *     price:float,
     *     type:string,
     *     start_date:string,
     *     end_date:string,
     *     view_count:int,
     *     comment_count:int,
     *     wish_count:int,
     *     languages:array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Course
    {
        return DB::transaction(function () use ($payload) {
            // Map legacy payload to new schema
            $courseTemplateId = Arr::get($payload, 'course_template_id', CourseTemplate::query()->value('id') ?? 1);
            $termId           = Arr::get($payload, 'term_id', Term::query()->value('id') ?? 1);

            $model = Course::create(Arr::only([
                'course_template_id' => $courseTemplateId,
                'term_id'            => $termId,
                'teacher_id'         => Arr::get($payload, 'teacher_id'),
                'capacity'           => Arr::get($payload, 'capacity'),
                'price'              => Arr::get($payload, 'price', 0),
                'type'               => Arr::get($payload, 'type'),
                'status'             => Arr::get($payload, 'status'),
                'days_of_week'       => Arr::get($payload, 'days_of_week'),
                'start_time'         => Arr::get($payload, 'start_time'),
                'end_time'           => Arr::get($payload, 'end_time'),
                'room_id'            => Arr::get($payload, 'room_id'),
                'meeting_link'       => Arr::get($payload, 'meeting_link'),
            ], [
                'course_template_id', 'term_id', 'teacher_id', 'capacity', 'price', 'type', 'status',
                'days_of_week', 'start_time', 'end_time', 'room_id', 'meeting_link',
            ]));

            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));

            return $model->refresh();
        });
    }
}
