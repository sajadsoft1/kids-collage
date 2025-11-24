<?php

declare(strict_types=1);

namespace App\Pipelines\Auth\Shared;

use App\Models\User;
use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\AuthInterface;
use Closure;

readonly class FindUserPipe implements AuthInterface
{
    public function handle(AuthDTO $dto, Closure $next): AuthDTO
    {
        $user = User::where('mobile', $dto->getFromPayload('mobile'))->first();
        abort_if( ! $user, 401, trans('auth.you_not_registered'));
        $dto->setUser($user);

        return $next($dto);
    }
}
