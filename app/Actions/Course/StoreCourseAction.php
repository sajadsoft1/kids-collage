<?php

declare(strict_types=1);

namespace App\Actions\Course;

use App\Actions\CourseSession\StoreCourseSessionAction;
use App\Enums\CourseStatusEnum;
use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\Course;
use App\Models\CourseTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseAction
{
    use AsAction;

    public function __construct(
        private readonly StoreCourseSessionAction $storeCourseSessionAction,
    ) {}

    /**
     * @param array{
     *     course_template_id:int,
     *     term_id:int,
     *     teacher_id:int,
     *     price:float,
     *     capacity:float,
     *     sessions:array{
     *         course_session_template_id:int,
     *         date:string,
     *         start_time:string,
     *         end_time:string,
     *         room_id:int|null,
     *         meeting_link:string|null,
     *         session_type?:SessionType,
     *         status?:SessionStatus,
     *     }[],
     * } $payload
     * @throws Throwable
     */
    public function handle(CourseTemplate $courseTemplate, array $payload): Course
    {
        return DB::transaction(function () use ($courseTemplate, $payload) {
            $model = Course::create([
                'course_template_id' => $courseTemplate->id,
                'term_id'            => Arr::get($payload, 'term_id'),
                'teacher_id'         => Arr::get($payload, 'teacher_id'),
                'capacity'           => Arr::get($payload, 'capacity'),
                'status'             => Arr::get($payload, 'status') ?? CourseStatusEnum::DRAFT->value,
                'price'              => Arr::get($payload, 'price', 0),
            ]);

            foreach ($payload['sessions'] as $session) {
                $this->storeCourseSessionAction->handle(array_merge($session, [
                    'course_id' => $model->id,
                ]));
            }

            return $model->refresh();
        });
    }
}
