<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserResource;
use App\Actions\User\StoreUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(User::class);
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     operationId="getUsers",
     *     tags={"User"},
     *     summary="get users list",
     *     description="Returns list of users",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResource")),
                         *             @OA\Property(property="links", type="object",
                         *                 @OA\Property(property="first", type="string", default="http://localhost/api/user?page=1"),
                         *                 @OA\Property(property="last", type="string", default="http://localhost/api/user?page=4"),
                         *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
                         *                 @OA\Property(property="next", type="string", default="http://localhost/api/user?page=2", nullable=true),
                         *             ),
                         *             @OA\Property(property="meta", ref="#/components/schemas/Meta"),
                         *             @OA\Property(property="message", type="string", default="No message"),
                         *             @OA\Property(property="advance_search_field", type="array",
                         *
                         *                 @OA\Items(type="object",
                         *
                         *                     @OA\Property(property="key", type="string", default="id"),
                         *                     @OA\Property(property="label", type="string", default="text"),
                         *                     @OA\Property(property="type", type="string", default="number"),
                         *                 ),
                         *             ),
                         *             @OA\Property(property="extra", type="object",
                         *                 @OA\Property(property="default_sort", type="string", default="-id"),
                         *                 @OA\Property(property="sorts", type="array", @OA\Items(type="string"), default={"id", "created_at", "updated_at"}),
                         *             ),
     *          )
     *     )
     *     )
     */
    public function index(UserRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            UserResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(User::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/user/{user}",
     *     operationId="getUserByID",
     *     tags={"User"},
     *     summary="Get user information",
     *     description="Returns user data",
     *     @OA\Parameter(name="user", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/UserDetailResource")
     *          )
     *      )
     * )
     */
    public function show(User $user): JsonResponse
    {
        return Response::data(
            UserDetailResource::make($user),
        );
    }

    /**
     * @OA\Post(
     *     path="/user",
     *     operationId="storeUser",
     *     tags={"User"},
     *     summary="Store new user",
     *     description="Returns new user data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreUserRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreUserRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="user has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/UserResource")
     *          )
     *      )
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $model = StoreUserAction::run($request->validated());
        return Response::data(
            UserResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('user.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/user/{user}",
     *     operationId="updateUser",
     *     tags={"User"},
     *     summary="Update existing user",
     *     description="Returns updated user data",
     *     @OA\Parameter(name="user",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateUserRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="user has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/UserResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = UpdateUserAction::run($user, $request->validated());
        return Response::data(
            UserResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('user.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/user/{user}",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="Delete existing user",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="user",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="user has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        DeleteUserAction::run($user);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('user.model')]),
            Response::HTTP_OK
        );
    }

//    /**
//     * @OA\Post(
//     *     path="/user/toggle/{user}",
//     *     operationId="toggleUser",
//     *     tags={"User"},
//     *     summary="Toggle User",
//     *     @OA\Parameter(name="user", required=true, in="path", @OA\Schema(type="integer")),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="user has been toggled successfully"),
//     *             @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource")
//     *         )
//     *     )
//     * )
//     */
//    public function toggle(User $user): JsonResponse
//    {
//        $this->authorize('update', $user);
//        $user = ToggleUserAction::run($user);
//
//        return Response::data(
//            UserResource::make($user),
//            trans('general.model_has_toggled_successfully', ['model' => trans('user.model')]),
//            Response::HTTP_OK
//        );
//    }
//
//    /**
//     * @OA\Get(
//     *     path="/user/data",
//     *     operationId="getUserData",
//     *     tags={"User"},
//     *     summary="Get User data",
//     *     description="Returns User data",
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="No message"),
//     *             @OA\Property(property="data", type="object",
//     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource")
//     *             )
//     *         )
//     *     )
//     * )
//     */
//    public function extraData(Request $request): JsonResponse
//    {
//        $this->authorize('create', User::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
