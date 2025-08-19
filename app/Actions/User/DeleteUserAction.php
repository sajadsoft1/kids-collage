<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserAction
{
    use AsAction;

    public function handle(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            return $user->delete();
        });
    }
}
