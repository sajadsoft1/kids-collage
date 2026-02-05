<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserBasicAction
{
    use AsAction;

    /**
     * Update user and profile basic fields only.
     *
     * @param  array{name?:string, family?:string, email?:string, status?:bool, mobile?:string, gender?:string, birth_date?:string|null, national_code?:string|null, address?:string, phone?:string, religion?:string, biography?:string, sickness?:string, delivery_recipient?:string} $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $user->update(Arr::only($payload, ['name', 'family', 'email', 'status', 'mobile']));

            $profileFields = ['gender', 'birth_date', 'national_code', 'address', 'phone', 'religion'];
            $profilePayload = [];
            foreach ($profileFields as $field) {
                if (array_key_exists($field, $payload)) {
                    $profilePayload[$field] = $payload[$field];
                }
            }
            if (array_key_exists('national_code', $profilePayload)) {
                $profilePayload['national_code'] = ! empty($profilePayload['national_code']) ? $profilePayload['national_code'] : null;
            }
            if (array_key_exists('birth_date', $profilePayload)) {
                $profilePayload['birth_date'] = ! empty($profilePayload['birth_date']) ? $profilePayload['birth_date'] : null;
            }
            if ($profilePayload !== []) {
                $user->profile()->update($profilePayload);
            }
            $user->profile->extra_attributes->set('biography', Arr::get($payload, 'biography', ''));
            $user->profile->extra_attributes->set('sickness', Arr::get($payload, 'sickness', ''));
            $user->profile->extra_attributes->set('delivery_recipient', Arr::get($payload, 'delivery_recipient', ''));
            $user->profile->save();

            return $user->refresh();
        });
    }
}
