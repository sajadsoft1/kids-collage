<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Setting\DataSettingAction;
use App\Helpers\Constants;
use App\Http\Resources\UserDetailResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class SplashController extends Controller
{
    /**
     * @OA\Get(
     *     path="/splash",
     *     operationId="splashAdmin",
     *     tags={"Splash"},
     *     summary="splash",
     *     description="Returns splash",
     *
     *     @OA\Parameter(name="browser", in="query", required=true, description="Browser name", @OA\Schema(type="string", enum={"chrome", "firefox", "safari", "edge", "opera", "ie", "other"}, default="chrome")),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(type="object",
     *
     *             @OA\Property(property="message", type="string", default="example message"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="version", type="string", default="1.0.0"),
     *                 @OA\Property(property="supported_locales", type="array", @OA\Items(type="object", @OA\Property(property="label", type="string", default="English"), @OA\Property(property="value", type="string", default="en"))),
     *                 @OA\Property(property="user", ref="#/components/schemas/UserDetailResource"),
     *                 @OA\Property(property="roles", type="array", @OA\Items(type="string", default="Admin")),
     *                 @OA\Property(property="permissions", type="array", @OA\Items(type="string", default="Shared.Admin")),
     *             )
     *         )
     *     )
     * )
     */
    public function splash(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        return Response::data([
            'version'           => Constants::$VERSION,
            'active_license'    => true,
            'supported_locales' => $this->supportedLocales(),
            'user'              => $user ? UserDetailResource::make($user) : null,
            'roles'             => $user?->roles->pluck('name'),
            'permissions'       => $user?->getAllPermissions()->pluck('name'),
            'settings'          => DataSettingAction::run(),
        ]);
    }

    private function supportedLocales(): array
    {
        return [
            ['label' => 'English', 'value' => 'en'],
            ['label' => 'Persian', 'value' => 'fa'],
        ];
    }
}
