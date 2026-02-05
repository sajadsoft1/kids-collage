<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserResumeAction
{
    use AsAction;

    public function __construct(
        private FileService $fileService,
    ) {}

    /**
     * Update profile resume/education/courses descriptions and media.
     *
     * @param  array{resume_description?:string, education_description?:string, courses_description?:string, resume_image?:mixed, education_image?:mixed, courses_image?:mixed} $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        $user->profile->extra_attributes->set('resume_description', Arr::get($payload, 'resume_description', ''));
        $user->profile->extra_attributes->set('education_description', Arr::get($payload, 'education_description', ''));
        $user->profile->extra_attributes->set('courses_description', Arr::get($payload, 'courses_description', ''));
        $user->profile->save();

        $this->fileService->addMedia($user, Arr::get($payload, 'resume_image'), 'resume');
        $this->fileService->addMedia($user, Arr::get($payload, 'education_image'), 'education_certificates');
        $this->fileService->addMedia($user, Arr::get($payload, 'courses_image'), 'completed_courses');

        return $user->refresh();
    }
}
