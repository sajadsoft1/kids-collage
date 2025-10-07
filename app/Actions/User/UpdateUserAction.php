<?php

declare(strict_types=1);

namespace App\Actions\User;

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
    )
    {
    }

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
            $user->profile()->update(Arr::only($payload, [
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
            ]));
            $user->syncRoles(Arr::get($payload, 'rules', []));
            $this->fileService->addMedia($user, Arr::get($payload, 'avatar'), 'avatar');
            $this->fileService->addMedia($user, Arr::get($payload, 'national_card'), 'national_card');
            $this->fileService->addMedia($user, Arr::get($payload, 'birth_certificate'), 'birth_certificate');

            return $user->refresh();
        });
    }
}
