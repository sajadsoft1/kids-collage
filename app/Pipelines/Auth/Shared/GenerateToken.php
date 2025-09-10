<?php

declare(strict_types=1);

namespace App\Pipelines\Auth\Shared;

use App\Helpers\ErrorHelper;
use App\Helpers\Utils;
use App\Models\Scopes\BranchScope;
use App\Models\Shop;
use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\AuthInterface;
use App\Repositories\User\UserRepositoryInterface;
use Closure;

readonly class GenerateToken implements AuthInterface
{
    public function __construct() {}

    public function handle(AuthDTO $dto, Closure $next): AuthDTO
    {
        $user = $dto->getUser();
        if ( ! $user) {
            ErrorHelper::ValidationError([
                ['mobile' => trans('validation.exists', ['attribute' => 'mobile'])],
            ]);
        }

        $token = $user->createToken('test-token')->plainTextToken;

        if ( ! $token) {
            abort(400, trans('auth.token_creation_failed'));
        }

        $dto->setToken($token);

        return $next($dto);
    }
}
