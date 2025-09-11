<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CardFlow\DeleteCardFlowAction;
use App\Actions\CardFlow\StoreCardFlowAction;
use App\Actions\CardFlow\UpdateCardFlowAction;
use App\Http\Requests\StoreCardFlowRequest;
use App\Http\Requests\UpdateCardFlowRequest;
use App\Http\Resources\CardFlowDetailResource;
use App\Http\Resources\CardFlowResource;
use App\Models\CardFlow;
use App\Repositories\CardFlow\CardFlowRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class CardFlowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(CardFlow::class);
    }

    /**
     * @OA\Get(
     *     path="/card-flow",
     *     operationId="getCardFlows",
     *     tags={"CardFlow"},
     *     summary="get cardFlows list",
     *     description="Returns list of cardFlows",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CardFlowResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/card-flow?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/card-flow?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/card-flow?page=2", nullable=true),
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
    public function index(CardFlowRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            CardFlowResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(CardFlow::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/card-flow/{cardFlow}",
     *     operationId="getCardFlowByID",
     *     tags={"CardFlow"},
     *     summary="Get cardFlow information",
     *     description="Returns cardFlow data",
     *     @OA\Parameter(name="cardFlow", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/CardFlowDetailResource")
     *         )
     *     )
     * )
     */
    public function show(CardFlow $cardFlow): JsonResponse
    {
        return Response::data(
            CardFlowDetailResource::make($cardFlow),
        );
    }

    /**
     * @OA\Post(
     *     path="/card-flow",
     *     operationId="storeCardFlow",
     *     tags={"CardFlow"},
     *     summary="Store new cardFlow",
     *     description="Returns new cardFlow data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCardFlowRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreCardFlowRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="card-flow has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/CardFlowResource")
     *         )
     *     )
     * )
     */
    public function store(StoreCardFlowRequest $request): JsonResponse
    {
        $model = StoreCardFlowAction::run($request->validated());

        return Response::data(
            CardFlowResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('cardFlow.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/card-flow/{cardFlow}",
     *     operationId="updateCardFlow",
     *     tags={"CardFlow"},
     *     summary="Update existing cardFlow",
     *     description="Returns updated cardFlow data",
     *     @OA\Parameter(name="cardFlow", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCardFlowRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateCardFlowRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="card-flow has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/CardFlowResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateCardFlowRequest $request, CardFlow $cardFlow): JsonResponse
    {
        $data = UpdateCardFlowAction::run($cardFlow, $request->validated());

        return Response::data(
            CardFlowResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('cardFlow.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/card-flow/{cardFlow}",
     *     operationId="deleteCardFlow",
     *     tags={"CardFlow"},
     *     summary="Delete existing cardFlow",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="cardFlow", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="card-flow has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(CardFlow $cardFlow): JsonResponse
    {
        DeleteCardFlowAction::run($cardFlow);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('cardFlow.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/card-flow/toggle/{cardFlow}",
    //     *     operationId="toggleCardFlow",
    //     *     tags={"CardFlow"},
    //     *     summary="Toggle CardFlow",
    //     *     @OA\Parameter(name="cardFlow", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="card-flow has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/CardFlowResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(CardFlow $cardFlow): JsonResponse
    //    {
    //        $this->authorize('update', $cardFlow);
    //        $cardFlow = ToggleCardFlowAction::run($cardFlow);
    //
    //        return Response::data(
    //            CardFlowResource::make($cardFlow),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('cardFlow.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/card-flow/data",
    //     *     operationId="getCardFlowData",
    //     *     tags={"CardFlow"},
    //     *     summary="Get CardFlow data",
    //     *     description="Returns CardFlow data",
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
    //        $this->authorize('create', CardFlow::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
