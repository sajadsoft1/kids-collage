<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Role\DeleteRoleAction;
use App\Actions\Role\StoreRoleAction;
use App\Actions\Role\UpdateRoleAction;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleDetailResource;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Role::class);
    }

    /**
     * @OA\Get(
     *     path="/role",
     *     operationId="getRoles",
     *     tags={"Role"},
     *     summary="get roles list",
     *     description="Returns list of roles",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/RoleResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/role?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/role?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/role?page=2", nullable=true),
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
     *         )
     *     )
     * )
     */
    public function index(RoleRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            RoleResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Role::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/role/{role}",
     *     operationId="getRoleByID",
     *     tags={"Role"},
     *     summary="Get role information",
     *     description="Returns role data",
     *     @OA\Parameter(name="role", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/RoleDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Role $role): JsonResponse
    {
        return Response::data(
            RoleDetailResource::make($role),
        );
    }

    /**
     * @OA\Post(
     *     path="/role",
     *     operationId="storeRole",
     *     tags={"Role"},
     *     summary="Store new role",
     *     description="Returns new role data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreRoleRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreRoleRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="role has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RoleResource")
     *         )
     *     )
     * )
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $model = StoreRoleAction::run($request->validated());

        return Response::data(
            RoleResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('role.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/role/{role}",
     *     operationId="updateRole",
     *     tags={"Role"},
     *     summary="Update existing role",
     *     description="Returns updated role data",
     *     @OA\Parameter(name="role", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateRoleRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateRoleRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="role has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RoleResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $data = UpdateRoleAction::run($role, $request->validated());

        return Response::data(
            RoleResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('role.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/role/{role}",
     *     operationId="deleteRole",
     *     tags={"Role"},
     *     summary="Delete existing role",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="role", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="role has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Role $role): JsonResponse
    {
        DeleteRoleAction::run($role);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('role.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/role/toggle/{role}",
    //     *     operationId="toggleRole",
    //     *     tags={"Role"},
    //     *     summary="Toggle Role",
    //     *     @OA\Parameter(name="role", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="role has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/RoleResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(Role $role): JsonResponse
    //    {
    //        $this->authorize('update', $role);
    //        $role = ToggleRoleAction::run($role);
    //
    //        return Response::data(
    //            RoleResource::make($role),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('role.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/role/data",
    //     *     operationId="getRoleData",
    //     *     tags={"Role"},
    //     *     summary="Get Role data",
    //     *     description="Returns Role data",
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
    //        $this->authorize('create', Role::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
