<?php

declare(strict_types=1);

namespace App\Actions\CourseTemplate;

use App\Actions\CourseSessionTemplate\StoreCourseSessionTemplateAction;
use App\Actions\Translation\SyncTranslationAction;
use App\Enums\CourseTypeEnum;
use App\Models\CourseTemplate;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly StoreCourseSessionTemplateAction $storeCourseSessionTemplateAction,
        private readonly FileService $fileService,
        private readonly SeoOptionService $seoOptionService,
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
    public function handle(array $payload): CourseTemplate
    {
        return DB::transaction(function () use ($payload) {
            $model = CourseTemplate::create([
                'slug' => $payload['slug'],
                'category_id' => $payload['category_id'] ?? null,
                'course_template_level_id' => $payload['course_template_level_id'] ?? null,
                'certificate_template_id' => $payload['certificate_template_id'] ?? null,
                'type' => $payload['type'] ?? CourseTypeEnum::IN_PERSON->value,
                'prerequisites' => $payload['prerequisites'] ?? [],
                'is_self_paced' => $payload['type'] === CourseTypeEnum::SELF_PACED->value ? true : false,
            ]);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            $this->seoOptionService->create($model, Arr::only($payload, ['title', 'description']));
            if ($tags = Arr::get($payload, 'tags')) {
                $model->syncTags($tags);
            }

            foreach ($payload['sessions'] as $session) {
                $this->storeCourseSessionTemplateAction->handle(array_merge($session, [
                    'course_template_id' => $model->id,
                ]));
            }

            return $model->refresh();
        });
    }
}
