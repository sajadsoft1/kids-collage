<?php

declare(strict_types=1);

namespace App\Actions\CourseSession;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseSessionAction
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
    public function handle(CourseSession $courseSession, array $payload): CourseSession
    {
        return DB::transaction(function () use ($courseSession, $payload) {
            $courseSession->update($payload);
            $this->syncTranslationAction->handle($courseSession, Arr::only($payload, ['title', 'description']));

            return $courseSession->refresh();
        });
    }
}
