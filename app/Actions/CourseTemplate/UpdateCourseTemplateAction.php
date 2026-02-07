<?php

declare(strict_types=1);

namespace App\Actions\CourseTemplate;

use App\Actions\CourseSessionTemplate\StoreCourseSessionTemplateAction;
use App\Actions\CourseSessionTemplate\UpdateCourseSessionTemplateAction;
use App\Actions\Translation\SyncTranslationAction;
use App\Enums\CourseTypeEnum;
use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly StoreCourseSessionTemplateAction $storeCourseSessionTemplateAction,
        private readonly UpdateCourseSessionTemplateAction $updateCourseSessionTemplateAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     slug:string,
     *     category_id:int|null,
     *     course_template_level_id:int|null,
     *     certificate_template_id:int|null,
     *     prerequisites:array|null,
     *     is_self_paced:bool|null,
     *     type:string|null,
     *     title:string,
     *     description:string,
     *     body?:string,
     *     tags?:array<string>,
     *     image?:string|null,
     *     sessions:array{
     *     order:int,
     *     duration_minutes:int,
     *     type?:string,
     *     title:string,
     *     description:string,
     * }[],
     * } $payload
     * @throws Throwable
     */
    public function handle(CourseTemplate $courseTemplate, array $payload): CourseTemplate
    {
        return DB::transaction(function () use ($courseTemplate, $payload) {
            $courseTemplate->update([
                'category_id' => $payload['category_id'] ?? null,
                'course_template_level_id' => $payload['course_template_level_id'] ?? null,
                'certificate_template_id' => $payload['certificate_template_id'] ?? null,
                'type' => $payload['type'] ?? CourseTypeEnum::IN_PERSON->value,
                'prerequisites' => $payload['prerequisites'] ?? [],
                'is_self_paced' => $payload['is_self_paced'] ?? false,
            ]);
            $this->syncTranslationAction->handle($courseTemplate, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($courseTemplate, Arr::get($payload, 'image'));
            if ($tags = Arr::get($payload, 'tags')) {
                $courseTemplate->syncTags($tags);
            }

            // remove deleted sessions
            $existingOrders = array_map(fn ($session) => $session['order'], $payload['sessions']);
            $courseTemplate->sessionTemplates()->whereNotIn('order', $existingOrders)->delete();

            foreach ($payload['sessions'] as $session) {
                $courseSessionTemplate = CourseSessionTemplate::where('order', $session['order'])
                    ->where('course_template_id', $courseTemplate->id)
                    ->first();

                // create a new session if not exists
                if ( ! $courseSessionTemplate) {
                    $this->storeCourseSessionTemplateAction->handle(array_merge($session, [
                        'course_template_id' => $courseTemplate->id,
                    ]));

                    continue;
                }

                // update existing session
                $this->updateCourseSessionTemplateAction->handle($courseSessionTemplate, $session);
            }

            return $courseTemplate->refresh();
        });
    }
}
