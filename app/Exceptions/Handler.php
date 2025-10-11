<?php

declare(strict_types=1);

namespace App\Exceptions;

use BadMethodCallException;
use Error;
use ErrorException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = []; // @phpstan-ignore-line

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        Log::info('Livewire Error: ' . $e->getMessage());
        // Handle Livewire requests with JSON response
        if ($request->is('livewire/*') && $e instanceof Exception) {
            Log::info('Livewire Error inside render: ' . $e->getMessage());
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
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'status'    => $statusCode,
                'url'       => $request->fullUrl(),
            ]);

            // Determine the message to show
            $message = $e->getMessage();

            // If not in debug mode and message is empty/generic, use friendly defaults
            if ( ! config('app.debug') && (empty($message) || $message === 'Server Error')) {
                $message = match ($statusCode) {
                    400     => __('درخواست نامعتبر است.'),
                    403     => __('شما اجازه انجام این عملیات را ندارید.'),
                    419     => __('نشست شما منقضی شده است.'),
                    422     => __('اطلاعات ارسالی نامعتبر است.'),
                    default => __('خطایی رخ داده است. لطفاً دوباره تلاش کنید.'),
                };
            }

            return response()->json([
                'message'   => $message,
                'exception' => config('app.debug') ? get_class($e) : null,
            ], $statusCode);
        }
        if ($request->expectsJson() && $request->is('api/*')) {
            // ************ add Accept: application/json in request is force ***************
            //            dd($exception);
            //            dd(get_class($e));

            if (config('app.env') === 'local') {
                return parent::render($request, $e);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'message'     => __('exception.address_not_found'),
                    'serverError' => $e->getMessage(),
                ], $e->getStatusCode());
            }
            if ($e instanceof TooManyRequestsHttpException) {
                return response()->json([
                    'message'     => __('exception.too_many_requests'),
                    'serverError' => $e->getMessage(),
                ], $e->getStatusCode());
            }
            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'message'     => __('exception.method_not_supported'),
                    'serverError' => $e->getMessage(),
                    'data'        => [],
                ], $e->getStatusCode());
            }
            if ($e instanceof HttpException) {
                return response()->json([
                    'message'     => $e->getMessage(),
                    'serverError' => $e->getMessage(),
                ], $e->getStatusCode());
            }
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'message'     => __('exception.data_not_found'),
                    'serverError' => $e->getMessage(),
                ], 404);
            }
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => ! empty($e->getMessage()) ? $e->getMessage() : __('exception.invalid_data_is_sent'),
                    'errors'  => $e->validator->errors(),
                ], $e->status);
            }
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message'     => __('auth.unauthenticated'),
                    'serverError' => $e->getMessage(),
                ], 401);
            }
            if ($e instanceof BadMethodCallException) {
                return response()->json([
                    'message'     => __('exception.calling_function_failed'),
                    'serverError' => $e->getMessage(),
                ], 500);
            }
            if ($e instanceof ErrorException) {
                return response()->json([
                    'message'     => __('exception.server_side_error'),
                    'serverError' => $e->getMessage(),
                ], 500);
            }
            if ($e instanceof Error) {
                return response()->json([
                    'message'     => __('exception.server_side_error'),
                    'serverError' => $e->getMessage(),
                ], 500);
            }
            if ($e instanceof BadRequestException) {
                return response()->json([
                    'message'     => __('exception.invalid_request_sent'),
                    'serverError' => $e->getMessage(),
                ], 400);
            }
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'message'     => __('exception.unauthorized'),
                    'serverError' => $e->getMessage(),
                ], 403);
            }

            try {
                if (method_exists($e, 'getStatusCode')) {
                    $code = $e->getStatusCode();
                } elseif (method_exists($e, 'getCode')) {
                    $code = $e->getCode();
                } else {
                    $code = 500;
                }

                return response()->json(
                    [
                        'code'        => $code,
                        'message'     => $e->getMessage(),
                        'serverError' => null,
                    ],
                    500
                );
            } catch (Exception $e) {
                return response()->json(
                    [
                        'message'     => __('exception.unexpected_error'),
                        'serverError' => $e->getMessage(),
                        'errors'      => $e->getTrace(),
                    ],
                    500
                );
            }
        }

        return parent::render($request, $e);
    }

    public function register(): void
    {
        //        $this->reportable(function (Throwable $e) {
        // //            if (app()->bound('sentry')) {
        // //                Integration::captureUnhandledException($e);
        // //            }
        // //        });
    }
}
