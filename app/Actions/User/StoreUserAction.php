<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Random\RandomException;
use Throwable;

readonly class StoreUserAction
{
    use AsAction;

    /**
     * Create user with all payload (delegates to StoreUserBasicAction then Update* actions for backward compatibility).
     *
     * @param  array<string, mixed> $payload
     * @throws Throwable
     * @throws RandomException
     */
    public function handle(array $payload): User
    {
        $payload['password'] ??= Hash::make($payload['mobile']);
        $basicPayload = Arr::only($payload, [
            'name', 'family', 'email', 'password', 'status', 'mobile', 'type',
            'gender', 'birth_date', 'national_code', 'address', 'phone', 'religion',
            'biography', 'sickness', 'delivery_recipient',
        ]);
        $user = StoreUserBasicAction::run($basicPayload);

        UpdateUserImagesAction::run($user, $payload);
        UpdateUserParentsAction::run($user, $payload);
        UpdateUserSalaryAction::run($user, $payload);
        UpdateUserResumeAction::run($user, $payload);
        UpdateUserSettingsAction::run($user, [
            'status' => $payload['status'] ?? true,
            'rules' => $payload['roles'] ?? $payload['rules'] ?? [],
        ]);

        return $user->refresh();
    }
}
