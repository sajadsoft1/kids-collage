<?php

declare(strict_types=1);

namespace App\Pipelines\Auth\Shared;

use App\Helpers\ErrorHelper;
use App\Models\UserOtp;
use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\AuthInterface;
use Closure;

readonly class GenerateOtpCodePipe implements AuthInterface
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

        $otp = rand(111111, 999999);

        if ( ! $otp) {
            abort(400, trans('auth.token_creation_failed'));
        }

        $user_otp = new UserOtp;
        $user_otp->user_id = $user->id;
        $user_otp->otp = $otp;
        $user_otp->ip_address = request()?->ip();
        $user_otp->save();

        $dto->setOtp((string) $otp);

        return $next($dto);
    }
}
