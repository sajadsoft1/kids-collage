<?php

declare(strict_types=1);

namespace App\Pipelines\Auth\Shared;

use App\Helpers\ErrorHelper;
use App\Models\User;
use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\AuthInterface;
use Closure;

readonly class CheckUserExistOrNotPipe implements AuthInterface
{
    public function handle(AuthDTO $dto, Closure $next): AuthDTO
    {
        $user = User::where('mobile', $dto->getFromPayload('mobile'))->first();
        ErrorHelper::ValidationErrorIf( ! $user, [
            ['mobile' => 'User not found'],
        ]);

        $dto->setUser($user);

        return $next($dto);
    }
}
