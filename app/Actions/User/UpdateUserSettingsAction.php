<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserSettingsAction
{
    use AsAction;

    /**
     * Update user status and roles only.
     *
     * @param  array{status?:bool, rules?:array<int>} $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        if (array_key_exists('status', $payload)) {
            $user->update(['status' => $payload['status']]);
        }
        if (isset($payload['rules'])) {
            $user->syncRoles(Arr::get($payload, 'rules', []));
        }

        return $user->refresh();
    }
}
