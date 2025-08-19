<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreUserAction
{
    use AsAction;

    public function __construct(
        private FileService $fileService
    ) {}

    /**
     * @param array{
     *     name:string,
     *     family:string,
     *     email:string,
     *     password:string,
     *     status:bool
     *     } $payload
     * @throws Throwable
     */
    public function handle(array $payload): User
    {
        return DB::transaction(function () use ($payload) {
            /** @var User $model */
            $payload['password'] = Hash::make($payload['password']);

            $user = User::create($payload);
            $user->syncRoles(Arr::get($payload, 'rules', []));
            $this->fileService->addMedia($user, Arr::get($payload, 'avatar'), 'avatar');

            return $user->refresh();
        });
    }
}
