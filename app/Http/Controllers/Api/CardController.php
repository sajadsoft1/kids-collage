<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateCardRequest;
use App\Http\Requests\StoreCardRequest;
use App\Http\Resources\CardDetailResource;
use App\Http\Resources\CardResource;
use App\Actions\Card\StoreCardAction;
use App\Actions\Card\DeleteCardAction;
use App\Actions\Card\UpdateCardAction;
use App\Repositories\Card\CardRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class CardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Card::class);
    }

    /**
     * @OA\Get(
     *     path="/card",
     *     operationId="getCards",
     *     tags={"Card"},
     *     summary="get cards list",
     *     description="Returns list of cards",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CardResource")),
                         *             @OA\Property(property="links", type="object",
                         *                 @OA\Property(property="first", type="string", default="http://localhost/api/card?page=1"),
                         *                 @OA\Property(property="last", type="string", default="http://localhost/api/card?page=4"),
                         *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
                         *                 @OA\Property(property="next", type="string", default="http://localhost/api/card?page=2", nullable=true),
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
    public function index(CardRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            CardResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Card::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/card/{card}",
     *     operationId="getCardByID",
     *     tags={"Card"},
     *     summary="Get card information",
     *     description="Returns card data",
     *     @OA\Parameter(name="card", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/CardDetailResource")
     *          )
     *      )
     * )
     */
    public function show(Card $card): JsonResponse
    {
        return Response::data(
            CardDetailResource::make($card),
        );
    }

    /**
     * @OA\Post(
     *     path="/card",
     *     operationId="storeCard",
     *     tags={"Card"},
     *     summary="Store new card",
     *     description="Returns new card data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreCardRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreCardRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="card has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/CardResource")
     *          )
     *      )
     * )
     */
    public function store(StoreCardRequest $request): JsonResponse
    {
        $model = StoreCardAction::run($request->validated());
        return Response::data(
            CardResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('card.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/card/{card}",
     *     operationId="updateCard",
     *     tags={"Card"},
     *     summary="Update existing card",
     *     description="Returns updated card data",
     *     @OA\Parameter(name="card",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCardRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateCardRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="card has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/CardResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateCardRequest $request, Card $card): JsonResponse
    {
        $data = UpdateCardAction::run($card, $request->validated());
        return Response::data(
            CardResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('card.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/card/{card}",
     *      operationId="deleteCard",
     *      tags={"Card"},
     *      summary="Delete existing card",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="card",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="card has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(Card $card): JsonResponse
    {
        DeleteCardAction::run($card);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('card.model')]),
            Response::HTTP_OK
        );
    }

//    /**
//     * @OA\Post(
//     *     path="/card/toggle/{card}",
//     *     operationId="toggleCard",
//     *     tags={"Card"},
//     *     summary="Toggle Card",
//     *     @OA\Parameter(name="card", required=true, in="path", @OA\Schema(type="integer")),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="card has been toggled successfully"),
//     *             @OA\Property(property="data", type="object", ref="#/components/schemas/CardResource")
//     *         )
//     *     )
//     * )
//     */
//    public function toggle(Card $card): JsonResponse
//    {
//        $this->authorize('update', $card);
//        $card = ToggleCardAction::run($card);
//
//        return Response::data(
//            CardResource::make($card),
//            trans('general.model_has_toggled_successfully', ['model' => trans('card.model')]),
//            Response::HTTP_OK
//        );
//    }
//
//    /**
//     * @OA\Get(
//     *     path="/card/data",
//     *     operationId="getCardData",
//     *     tags={"Card"},
//     *     summary="Get Card data",
//     *     description="Returns Card data",
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
//        $this->authorize('create', Card::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
