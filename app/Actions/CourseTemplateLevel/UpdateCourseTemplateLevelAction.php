<?php

declare(strict_types=1);

namespace App\Actions\CourseTemplateLevel;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseTemplateLevel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseTemplateLevelAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(CourseTemplateLevel $courseTemplateLevel, array $payload): CourseTemplateLevel
    {
        return DB::transaction(function () use ($courseTemplateLevel, $payload) {
            $courseTemplateLevel->update(Arr::only($payload, ['published', 'languages']));
            $this->syncTranslationAction->handle($courseTemplateLevel, Arr::only($payload, ['title', 'description']));

            return $courseTemplateLevel->refresh();
        });
    }
}
