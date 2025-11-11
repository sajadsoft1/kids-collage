<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\SimpleUserResource;
use App\Http\Resources\UserDetailResource;
use App\Pipelines\Auth\AuthDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout', 'me');
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="Login user",
     *     description="Login user",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(type="object",
     *
     *             @OA\Property(property="message", type="string", default="example message"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", default="example token"),
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="400", ref="#/components/responses/400"),
     *     @OA\Response(response="401", ref="#/components/responses/401"),
     *     @OA\Response(response="403", ref="#/components/responses/403"),
     *     @OA\Response(response="500", ref="#/components/responses/500")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        /** @var AuthDTO $dto */
        $dto = LoginAction::run($request->validated());

        return Response::data([
            'token' => $dto->getToken(),
            'user' => SimpleUserResource::make($dto->getUser()),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     operationId="me",
     *     tags={"Auth"},
     *     summary="get user data",
     *
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(type="object",
     *
     *             @OA\Property(property="message", type="string", default="example message"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="400", ref="#/components/responses/400"),
     *     @OA\Response(response="401", ref="#/components/responses/401"),
     *     @OA\Response(response="403", ref="#/components/responses/403"),
     *     @OA\Response(response="500", ref="#/components/responses/500")
     * )
     */
    public function me(): JsonResponse
    {
        return Response::data([
            'user' => UserDetailResource::make(auth()->user()->load('roles', 'profile')),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/auth/logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *     summary="Logout user",
     *     description="Logout user",
     *
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(type="object",
     *
     *             @OA\Property(property="message", type="string", default="example message"),
     *             @OA\Property(property="data", type="boolean", default=true)
     *         )
     *     ),
     *
     *     @OA\Response(response="400", ref="#/components/responses/400"),
     *     @OA\Response(response="401", ref="#/components/responses/401"),
     *     @OA\Response(response="403", ref="#/components/responses/403"),
     *     @OA\Response(response="500", ref="#/components/responses/500")
     * )
     */
    public function logout(): JsonResponse
    {
        LogoutAction::run(auth()->user());

        return Response::data(true, trans('auth.You_have_successfully_logged_out'));
    }
}
