<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\Shared\FindUserPipe;
use App\Pipelines\Auth\Shared\GenerateOtpCodePipe;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class ForgetPasswordAction
{
    use AsAction;

    /**
     * @param array{
     *     mobile:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): AuthDTO
    {
        return DB::transaction(function () use ($payload) {
            $dto = new AuthDTO($payload);

            return app(Pipeline::class)
                ->send($dto)
                ->through([
                    FindUserPipe::class,
                    GenerateOtpCodePipe::class,
                ])
                ->then(function (AuthDTO $dto) {
                    return $dto;
                });
        });
    }
}
