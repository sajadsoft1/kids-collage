<?php

declare(strict_types=1);

namespace App\Pipelines\Auth\Shared;

use App\Models\User;
use App\Models\UserOtp;
use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\AuthInterface;
use Closure;

readonly class CheckOtpPipe implements AuthInterface
{
    public function handle(AuthDTO $dto, Closure $next): AuthDTO
    {
        $user = User::where('mobile', $dto->getFromPayload('mobile'))->first();
        abort_if( ! $user, 401, trans('auth.invalid_code'));
        $dto->setUser($user);
        $userOtp = UserOtp::where('user_id', $user->id)->orderByDesc('id')->first();
        abort_if( ! $userOtp, 401, trans('auth.invalid_code'));
        abort_if($userOtp->otp !== $dto->getFromPayload('otp'), 401, trans('auth.invalid_code'));
        abort_if($userOtp->used_at != null, 401, trans('auth.invalid_code'));
        $userOtp->update([
            'used_at' => now(),
        ]);

        return $next($dto);
    }
}
