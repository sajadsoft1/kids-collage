<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserParentsAction
{
    use AsAction;

    /**
     * Update profile parent fields and parent/child relations.
     *
     * @param  array{type?:string, father_id?:int, mother_id?:int, children_id?:array<int>, father_name?:string, father_phone?:string, mother_name?:string, mother_phone?:string, father_age?:string, father_education?:string, father_workplace?:string, mother_age?:string, mother_education?:string, mother_workplace?:string, number_of_siblings?:int|null} $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $profilePayload = Arr::only($payload, [
                'father_name', 'father_phone', 'mother_name', 'mother_phone',
            ]);
            if ($profilePayload !== []) {
                $user->profile()->update($profilePayload);
            }
            $user->profile->extra_attributes->set('father_age', Arr::get($payload, 'father_age', ''));
            $user->profile->extra_attributes->set('father_education', Arr::get($payload, 'father_education', ''));
            $user->profile->extra_attributes->set('father_workplace', Arr::get($payload, 'father_workplace', ''));
            $user->profile->extra_attributes->set('mother_age', Arr::get($payload, 'mother_age', ''));
            $user->profile->extra_attributes->set('mother_education', Arr::get($payload, 'mother_education', ''));
            $user->profile->extra_attributes->set('mother_workplace', Arr::get($payload, 'mother_workplace', ''));
            $user->profile->extra_attributes->set('number_of_siblings', Arr::get($payload, 'number_of_siblings'));
            $user->profile->save();

            $type = Arr::get($payload, 'type');
            if ($type === UserTypeEnum::USER->value) {
                $parentsId = array_filter([
                    Arr::get($payload, 'father_id'),
                    Arr::get($payload, 'mother_id'),
                ]);
                $user->parents()->sync($parentsId);
            } elseif ($type === UserTypeEnum::PARENT->value) {
                $user->children()->sync(Arr::get($payload, 'children_id', []));
            }

            return $user->refresh();
        });
    }
}
