<?php

declare(strict_types=1);

namespace App\Pipelines\Auth\Login;

use App\Helpers\ErrorHelper;
use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\AuthInterface;
use Closure;

class CheckPassword implements AuthInterface
{
    public function handle(AuthDTO $dto, Closure $next): AuthDTO
    {
        if ( ! password_verify($dto->getFromPayload('password'), $dto->getUser()->password)) {
            ErrorHelper::ValidationError([
                ['password' => trans('auth.the_entered_information_is_not_correct')],
            ]);
        }

        return $next($dto);
    }
}
