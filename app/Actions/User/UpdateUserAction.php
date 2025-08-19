<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateUserAction
{
    use AsAction;

    public function __construct(
        private FileService $fileService,
    ) {}

    /**
     * @param  array{name:string,family:string,active:bool} $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $user->update($payload);
            $user->syncRoles(Arr::get($payload, 'rules', []));
            $this->fileService->addMedia($user, Arr::get($payload, 'avatar'), 'avatar');

            return $user->refresh();
        });
    }
}
