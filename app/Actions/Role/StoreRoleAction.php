<?php

declare(strict_types=1);

namespace App\Actions\Role;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreRoleAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     name:string,
     *     description?:string,
     *     permissions?:array<int>
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Role
    {
        return DB::transaction(function () use ($payload) {
            $model = Role::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['description']));

            if ($permissionIds = Arr::get($payload, 'permissions')) {
                $model->permissions()->sync($permissionIds);
            }

            return $model->refresh();
        });
    }
}
