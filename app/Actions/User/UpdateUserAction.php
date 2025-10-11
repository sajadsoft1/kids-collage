<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

readonly class UpdateUserAction
{
    use AsAction;

    public function __construct(
        private FileService $fileService,
    ) {}

    /**
     * @param array{
     * name:string,
     * family:string,
     * email:string,
     * status:bool,
     * mobile:string,
     * avatar:string,
     * type:string,
     * gender:string,
     * birth_date:string,
     * national_code:string,
     * address:string,
     * phone:string,
     * father_name:string,
     * father_phone:string,
     * mother_name:string,
     * mother_phone:string,
     * father_id?:int,
     * mother_id?:int,
     * children_id?:array<int>,
     * religion:string,
     * national_card:string,
     * birth_certificate:string,
     * salary:int,
     * benefit:int,
     * cooperation_start_date:string|null,
     * cooperation_end_date:string|null,
     * roles:array<string>,
     * } $payload
     * @throws Throwable
     */
    public function handle(User $user, array $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $user->update(Arr::only($payload, ['name', 'family', 'email', 'status', 'mobile']));

            $profileFields = [
                'gender',
                'birth_date',
                'national_code',
                'address',
                'phone',
                'father_name',
                'father_phone',
                'mother_name',
                'mother_phone',
                'religion',
                'salary',
                'benefit',
                'cooperation_start_date',
                'cooperation_end_date',
            ];

            $profilePayload = [];
            foreach ($profileFields as $field) {
                if (isset($payload[$field])) {
                    $profilePayload[$field] = $payload[$field];
                }
            }

            $user->profile()->update($profilePayload);
            if (isset($payload['rules'])) {
                $user->syncRoles(Arr::get($payload, 'rules', []));
            }
            $this->fileService->addMedia($user, Arr::get($payload, 'avatar'), 'avatar');
            $this->fileService->addMedia($user, Arr::get($payload, 'national_card'), 'national_card');
            $this->fileService->addMedia($user, Arr::get($payload, 'birth_certificate'), 'birth_certificate');

            if (Arr::get($payload, 'type') === UserTypeEnum::USER->value) {
                $parentsId = [];
                if (Arr::get($payload, 'father_id')) {
                    $parentsId[] = Arr::get($payload, 'father_id');
                }
                if (Arr::get($payload, 'mother_id')) {
                    $parentsId[] = Arr::get($payload, 'mother_id');
                }
                $user->parents()->attach($parentsId);
            } elseif (Arr::get($payload, 'type') === UserTypeEnum::PARENT->value) {
                $user->children()->sync(Arr::get($payload, 'children_id', []));
            }

            return $user->refresh();
        });
    }
}
