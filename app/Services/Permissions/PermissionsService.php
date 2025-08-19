<?php

declare(strict_types=1);

namespace App\Services\Permissions;

use App\Helpers\StringHelper;
use App\Services\Permissions\Models\SharedPermissions;
use App\Services\Permissions\Services\CorePermissions;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PermissionsService
{
    public static function showPermissionsByService(array $services = ['core']): Collection
    {
        $permissions = [];

        $permissions = array_merge($permissions, CorePermissions::all());

        $permissions[] = resolve(SharedPermissions::class)->all();

        return collect($permissions)
            ->unique('group');
    }

    public static function generatePermissionsByModel(string $model, ...$permissions): array
    {
        $res    = ['Shared.Admin'];
        $prefix = StringHelper::basename($model);
        $res[]  = $prefix . '.' . 'All';
        foreach ($permissions as $permission) {
            $res[] = $prefix . '.' . Str::studly($permission);
        }

        return array_unique($res);
    }
}
