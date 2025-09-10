<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Pipelines\Auth\AuthDTO;
use App\Pipelines\Auth\Login\CheckPassword;
use App\Pipelines\Auth\Shared\CheckUserExistOrNotPipe;
use App\Pipelines\Auth\Shared\GenerateToken;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class LoginAction
{
    use AsAction;

    /**
     * @param array{
     *     mobile:string,
     *     password:string,
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
                    CheckUserExistOrNotPipe::class,
                    CheckPassword::class,
                    GenerateToken::class,
                ])
                ->then(function (AuthDTO $dto) {
                    return $dto;
                });
        });
    }
}
