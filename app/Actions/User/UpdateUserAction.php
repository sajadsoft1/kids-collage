<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserAction
{
    use AsAction;

    /**
     * Update user with full payload (delegates to tab-specific Update* actions for backward compatibility).
     *
     * @param  array<string, mixed> $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        UpdateUserBasicAction::run($user, $payload);
        UpdateUserImagesAction::run($user, $payload);
        UpdateUserParentsAction::run($user, $payload);
        UpdateUserSalaryAction::run($user, $payload);
        UpdateUserResumeAction::run($user, $payload);
        UpdateUserSettingsAction::run($user, [
            'status' => $payload['status'] ?? $user->status->value,
            'rules' => $payload['rules'] ?? $payload['selected_rules'] ?? [],
        ]);

        return $user->refresh();
    }
}
