<?php

declare(strict_types=1);

namespace App\Pipelines\Auth\Shared;

use App\Models\User;
use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\AuthInterface;
use Closure;

readonly class CreateUserPipe implements AuthInterface
{
    public function handle(AuthDTO $dto, Closure $next): AuthDTO
    {
        $user = User::where('mobile', $dto->getFromPayload('mobile'))->first();
        if ( ! $user) {
            $user = User::create(
                [
                    'mobile' => $dto->getFromPayload('mobile'),
                    'name' => $dto->getFromPayload('name'),
                    'family' => $dto->getFromPayload('family'),
                    'password' => bcrypt($dto->getFromPayload('password')),
                ]
            );
            $dto->setUser($user);
        }
        abort_if($user->mobile_verified_at != null, 401, trans('auth.already_have_account'));
        $dto->setUser($user);

        return $next($dto);
    }
}
