<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Branch\DeleteBranchAction;
use App\Actions\Branch\StoreBranchAction;
use App\Actions\Branch\ToggleBranchAction;
use App\Actions\Branch\UpdateBranchAction;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchDetailResource;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Repositories\Branch\BranchRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Branch::class);
    }

    /**
     * @OA\Get(
     *     path="/branch",
     *     operationId="getBranchs",
     *     tags={"Branch"},
     *     summary="get branchs list",
     *     description="Returns list of branchs",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BranchResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/branch?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/branch?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/branch?page=2", nullable=true),
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
    public function index(BranchRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            BranchResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Branch::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/branch/{branch}",
     *     operationId="getBranchByID",
     *     tags={"Branch"},
     *     summary="Get branch information",
     *     description="Returns branch data",
     *     @OA\Parameter(name="branch", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/BranchDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Branch $branch): JsonResponse
    {
        return Response::data(
            BranchDetailResource::make($branch),
        );
    }

    /**
     * @OA\Post(
     *     path="/branch",
     *     operationId="storeBranch",
     *     tags={"Branch"},
     *     summary="Store new branch",
     *     description="Returns new branch data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreBranchRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreBranchRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="branch has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/BranchResource")
     *         )
     *     )
     * )
     */
    public function store(StoreBranchRequest $request): JsonResponse
    {
        $model = StoreBranchAction::run($request->validated());

        return Response::data(
            BranchResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('branch.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/branch/{branch}",
     *     operationId="updateBranch",
     *     tags={"Branch"},
     *     summary="Update existing branch",
     *     description="Returns updated branch data",
     *     @OA\Parameter(name="branch", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateBranchRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateBranchRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="branch has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/BranchResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateBranchRequest $request, Branch $branch): JsonResponse
    {
        $data = UpdateBranchAction::run($branch, $request->validated());

        return Response::data(
            BranchResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('branch.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/branch/{branch}",
     *     operationId="deleteBranch",
     *     tags={"Branch"},
     *     summary="Delete existing branch",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="branch", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="branch has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Branch $branch): JsonResponse
    {
        DeleteBranchAction::run($branch);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('branch.model')]),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *     path="/branch/toggle/{branch}",
     *     operationId="toggleBranch",
     *     tags={"Branch"},
     *     summary="Toggle Branch",
     *     @OA\Parameter(name="branch", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="branch has been toggled successfully"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/BranchResource")
     *         )
     *     )
     * )
     */
    public function toggle(Branch $branch): JsonResponse
    {
        $this->authorize('update', $branch);
        $branch = ToggleBranchAction::run($branch);

        return Response::data(
            BranchResource::make($branch),
            trans('general.model_has_toggled_successfully', ['model' => trans('branch.model')]),
            Response::HTTP_OK
        );
    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/branch/data",
    //     *     operationId="getBranchData",
    //     *     tags={"Branch"},
    //     *     summary="Get Branch data",
    //     *     description="Returns Branch data",
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
    //        $this->authorize('create', Branch::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
