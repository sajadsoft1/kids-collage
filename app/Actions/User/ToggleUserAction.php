<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ToggleUserAction
{
    use AsAction;

    public function __construct(private readonly UserRepositoryInterface $repository) {}

    public function handle(User $user): User
    {
        return DB::transaction(function () use ($user) {
            /** @var User $model */
            $model =  $this->repository->toggle($user);

            return $model->refresh();
        });
    }
}
