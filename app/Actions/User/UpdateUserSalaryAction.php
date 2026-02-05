<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserSalaryAction
{
    use AsAction;

    /**
     * Update profile salary, benefit, cooperation dates only.
     *
     * @param  array{salary?:float, benefit?:float, cooperation_start_date?:string|null, cooperation_end_date?:string|null} $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        $profilePayload = Arr::only($payload, [
            'salary', 'benefit', 'cooperation_start_date', 'cooperation_end_date',
        ]);
        if ($profilePayload !== []) {
            $user->profile()->update($profilePayload);
        }

        return $user->refresh();
    }
}
