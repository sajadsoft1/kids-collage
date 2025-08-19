<?php

declare(strict_types=1);

namespace App\Actions\Role;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteRoleAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Role $role): bool
    {
        return DB::transaction(function () use ($role) {
            return $role->delete();
        });
    }
}
