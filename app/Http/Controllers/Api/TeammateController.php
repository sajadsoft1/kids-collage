<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Teammate\DeleteTeammateAction;
use App\Actions\Teammate\StoreTeammateAction;
use App\Actions\Teammate\UpdateTeammateAction;
use App\Http\Requests\StoreTeammateRequest;
use App\Http\Requests\UpdateTeammateRequest;
use App\Http\Resources\TeammateDetailResource;
use App\Http\Resources\TeammateResource;
use App\Models\Teammate;
use App\Repositories\Teammate\TeammateRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class TeammateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Teammate::class);
    }

    /**
     * @OA\Get(
     *     path="/teammate",
     *     operationId="getTeammates",
     *     tags={"Teammate"},
     *     summary="get teammates list",
     *     description="Returns list of teammates",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TeammateResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/teammate?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/teammate?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/teammate?page=2", nullable=true),
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
    public function index(TeammateRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            TeammateResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Teammate::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/teammate/{teammate}",
     *     operationId="getTeammateByID",
     *     tags={"Teammate"},
     *     summary="Get teammate information",
     *     description="Returns teammate data",
     *     @OA\Parameter(name="teammate", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/TeammateDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Teammate $teammate): JsonResponse
    {
        return Response::data(
            TeammateDetailResource::make($teammate),
        );
    }

    /**
     * @OA\Post(
     *     path="/teammate",
     *     operationId="storeTeammate",
     *     tags={"Teammate"},
     *     summary="Store new teammate",
     *     description="Returns new teammate data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTeammateRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreTeammateRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="teammate has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/TeammateResource")
     *         )
     *     )
     * )
     */
    public function store(StoreTeammateRequest $request): JsonResponse
    {
        $model = StoreTeammateAction::run($request->validated());

        return Response::data(
            TeammateResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('teammate.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/teammate/{teammate}",
     *     operationId="updateTeammate",
     *     tags={"Teammate"},
     *     summary="Update existing teammate",
     *     description="Returns updated teammate data",
     *     @OA\Parameter(name="teammate", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTeammateRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateTeammateRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="teammate has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/TeammateResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateTeammateRequest $request, Teammate $teammate): JsonResponse
    {
        $data = UpdateTeammateAction::run($teammate, $request->validated());

        return Response::data(
            TeammateResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('teammate.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/teammate/{teammate}",
     *     operationId="deleteTeammate",
     *     tags={"Teammate"},
     *     summary="Delete existing teammate",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="teammate", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="teammate has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Teammate $teammate): JsonResponse
    {
        DeleteTeammateAction::run($teammate);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('teammate.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/teammate/toggle/{teammate}",
    //     *     operationId="toggleTeammate",
    //     *     tags={"Teammate"},
    //     *     summary="Toggle Teammate",
    //     *     @OA\Parameter(name="teammate", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="teammate has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/TeammateResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(Teammate $teammate): JsonResponse
    //    {
    //        $this->authorize('update', $teammate);
    //        $teammate = ToggleTeammateAction::run($teammate);
    //
    //        return Response::data(
    //            TeammateResource::make($teammate),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('teammate.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/teammate/data",
    //     *     operationId="getTeammateData",
    //     *     tags={"Teammate"},
    //     *     summary="Get Teammate data",
    //     *     description="Returns Teammate data",
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
    //        $this->authorize('create', Teammate::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
