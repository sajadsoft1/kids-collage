<?php

declare(strict_types=1);

use App\Http\Middleware\AdminLanguageMiddleware;
use App\Http\Middleware\AdminPanelMiddleware;
use App\Http\Middleware\ConvertEmptyStringsToNull;
use App\Http\Middleware\Cors;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\SeoRedirectMiddleware;
use App\Http\Middleware\SetBranchMiddleware;
use App\Http\Middleware\SwaggerHelperMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/admin.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->web([
            ChinLeung\MultilingualRoutes\DetectRequestLocale::class,
            SeoRedirectMiddleware::class,
            SetBranchMiddleware::class,
        ]);
        $middleware->api([
            EnsureFrontendRequestsAreStateful::class,
            SubstituteBindings::class,
            SetBranchMiddleware::class,
            Cors::class,
            ForceJsonResponse::class,
            ConvertEmptyStringsToNull::class,
            SwaggerHelperMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        $middleware->alias([
            'admin.panel' => AdminPanelMiddleware::class,
            'locale' => LanguageMiddleware::class,
            'locale.admin' => AdminLanguageMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle Livewire exceptions
        $exceptions->render(function (Throwable $e, Request $request) {
            // Check if this is a Livewire request (check both URL and header)
            $isLivewireRequest = $request->is('livewire/*') || $request->hasHeader('X-Livewire');

            if ($isLivewireRequest) {
                // Determine the appropriate status code
                $statusCode = 500;

                if ($e instanceof ValidationException) {
                    $statusCode = 422;
                } elseif ($e instanceof HttpException) {
                    $statusCode = $e->getStatusCode();
                } elseif ($e instanceof InvalidArgumentException) {
                    $statusCode = 400;
                }

                // Log the error for debugging
                Log::error('Livewire Error: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'status' => $statusCode,
                    'url' => $request->fullUrl(),
                ]);

                // Determine the message to show
                $message = $e->getMessage();

                if ( ! config('app.debug') && (empty($message) || $message === 'Server Error')) {
                    $message = match ($statusCode) {
                        400 => __('درخواست نامعتبر است.'),
                        403 => __('شما اجازه انجام این عملیات را ندارید.'),
                        419 => __('نشست شما منقضی شده است.'),
                        422 => __('اطلاعات ارسالی نامعتبر است.'),
                        default => __('خطایی رخ داده است. لطفاً دوباره تلاش کنید.'),
                    };
                }

                return response()->json([
                    'message' => $message,
                    'exception' => config('app.debug') ? get_class($e) : null,
                ], $statusCode);
            }
        });
    })
    ->withSchedule(function (Schedule $schedule) {
        // Run content publishing every minute
        $schedule->command('content:publish-scheduled')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground()
            ->onSuccess(function () {
                Log::info('Content publishing schedule completed successfully');
            })
            ->onFailure(function () {
                Log::error('Content publishing schedule failed');
            });

        $schedule->command('telescope:prune --hours=48')->daily();

        if (env('BACKUP_ENABLED', false)) {
            $schedule->exec('/scripts/backup.sh')
                ->daily()
                ->at(env('BACKUP_TIME', '02:00'))
                ->onOneServer()
                ->emailOutputOnFailure(env('MAIL_FROM_ADDRESS', 'sajadsoft12gmail.com'));
        }
    })
    ->create();
