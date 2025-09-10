<?php

declare(strict_types=1);

namespace App\Pipelines\Auth;

use Closure;

interface AuthInterface
{
    public function handle(AuthDTO $dto, Closure $next): AuthDTO;
}
