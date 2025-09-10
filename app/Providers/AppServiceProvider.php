<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    /** Bootstrap any application services. */
    public function boot(): void
    {
        Response::macro('data', function ($data = null, string $message = '', int $status = 200) {
            return response()->json(compact('message', 'data'), $status);
        });

        Response::macro('error', function (string $message = '', int $status = 400) {
            return response()->json(compact('message'), $status);
        });

        Response::macro('dataWithAdditional', function (AnonymousResourceCollection $data, array $additional = [], string $message = '', int $status = 200) {
            return $data->additional(array_merge(compact('message'), $additional))->response()->setStatusCode($status);
        });
    }
}
