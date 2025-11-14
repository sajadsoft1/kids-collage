<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class LogoutAction
{
    use AsAction;

    public function handle(User $user): void
    {
        $tokens = $user->tokens->pluck('id');

        // delete all tokens
        $user->tokens()->whereIn('id', $tokens)->delete();
    }
}
