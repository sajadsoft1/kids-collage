<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Random\RandomException;
use Throwable;

readonly class StoreUserBasicAction
{
    use AsAction;

    /**
     * Create user and profile with basic fields only (no media, parents, roles).
     *
     * @param  array{name:string, family:string, email:string|null, mobile:string, status:bool, type:string, gender:string|null, birth_date:string|null, national_code:string|null, address:string|null, phone:string|null, religion:string|null, biography?:string, sickness?:string, delivery_recipient?:string} $payload
     * @throws Throwable
     * @throws RandomException
     */
    public function handle(array $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $payload['password'] = Hash::make($payload['password'] ?? $payload['mobile']);

            $user = User::create(Arr::only($payload, ['name', 'family', 'email', 'password', 'status', 'mobile', 'type']));

            $profilePayload = Arr::only($payload, [
                'gender',
                'birth_date',
                'national_code',
                'address',
                'phone',
                'religion',
            ]);
            $profilePayload['national_code'] = ! empty($profilePayload['national_code'] ?? null) ? $profilePayload['national_code'] : null;
            $profilePayload['birth_date'] = ! empty($payload['birth_date'] ?? null) ? $payload['birth_date'] : null;
            $profilePayload['user_id'] = $user->id;
            $user->profile()->create($profilePayload);
            $user->profile->extra_attributes->set('biography', Arr::get($payload, 'biography', ''));
            $user->profile->extra_attributes->set('sickness', Arr::get($payload, 'sickness', ''));
            $user->profile->extra_attributes->set('delivery_recipient', Arr::get($payload, 'delivery_recipient', ''));
            $user->profile->save();

            return $user->refresh();
        });
    }
}
