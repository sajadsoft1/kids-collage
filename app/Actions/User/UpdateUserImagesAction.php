<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserImagesAction
{
    use AsAction;

    public function __construct(
        private FileService $fileService,
    ) {}

    /**
     * Update user avatar, national_card, birth_certificate media only.
     *
     * @param  array{avatar?:mixed, national_card?:mixed, birth_certificate?:mixed} $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        $this->fileService->addMedia($user, Arr::get($payload, 'avatar'), 'avatar');
        $this->fileService->addMedia($user, Arr::get($payload, 'national_card'), 'national_card');
        $this->fileService->addMedia($user, Arr::get($payload, 'birth_certificate'), 'birth_certificate');

        return $user->refresh();
    }
}
