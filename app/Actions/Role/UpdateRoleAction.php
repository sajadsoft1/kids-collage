<?php

declare(strict_types=1);

namespace App\Actions\Role;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateRoleAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     name:string,
     *     permissions?:array,
     *     description?:string,
     * }           $payload
     * @throws Throwable
     */
    public function handle(Role $role, array $payload): Role
    {
        return DB::transaction(function () use ($role, $payload) {
            $role->update($payload);
            $this->syncTranslationAction->handle($role, Arr::only($payload, ['description']));
            $role->permissions()->sync(Arr::get($payload, 'permissions', []));

            return $role->refresh();
        });
    }
}
