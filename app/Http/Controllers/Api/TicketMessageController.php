<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\TicketMessage\DeleteTicketMessageAction;
use App\Actions\TicketMessage\StoreTicketMessageAction;
use App\Actions\TicketMessage\UpdateTicketMessageAction;
use App\Http\Requests\StoreTicketMessageRequest;
use App\Http\Requests\UpdateTicketMessageRequest;
use App\Http\Resources\TicketMessageDetailResource;
use App\Http\Resources\TicketMessageResource;
use App\Models\TicketMessage;
use App\Repositories\TicketMessage\TicketMessageRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class TicketMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(TicketMessage::class);
    }

    /**
     * @OA\Get(
     *     path="/ticket-message",
     *     operationId="getTicketMessages",
     *     tags={"TicketMessage"},
     *     summary="get ticketMessages list",
     *     description="Returns list of ticketMessages",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TicketMessageResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/ticket-message?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/ticket-message?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/ticket-message?page=2", nullable=true),
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
    public function index(TicketMessageRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            TicketMessageResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(TicketMessage::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/ticket-message/{ticketMessage}",
     *     operationId="getTicketMessageByID",
     *     tags={"TicketMessage"},
     *     summary="Get ticketMessage information",
     *     description="Returns ticketMessage data",
     *     @OA\Parameter(name="ticketMessage", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/TicketMessageDetailResource")
     *         )
     *     )
     * )
     */
    public function show(TicketMessage $ticketMessage): JsonResponse
    {
        return Response::data(
            TicketMessageDetailResource::make($ticketMessage),
        );
    }

    /**
     * @OA\Post(
     *     path="/ticket-message",
     *     operationId="storeTicketMessage",
     *     tags={"TicketMessage"},
     *     summary="Store new ticketMessage",
     *     description="Returns new ticketMessage data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTicketMessageRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreTicketMessageRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="ticket-message has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/TicketMessageResource")
     *         )
     *     )
     * )
     */
    public function store(StoreTicketMessageRequest $request): JsonResponse
    {
        $model = StoreTicketMessageAction::run($request->validated());

        return Response::data(
            TicketMessageResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('ticketMessage.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/ticket-message/{ticketMessage}",
     *     operationId="updateTicketMessage",
     *     tags={"TicketMessage"},
     *     summary="Update existing ticketMessage",
     *     description="Returns updated ticketMessage data",
     *     @OA\Parameter(name="ticketMessage", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTicketMessageRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateTicketMessageRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="ticket-message has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/TicketMessageResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateTicketMessageRequest $request, TicketMessage $ticketMessage): JsonResponse
    {
        $data = UpdateTicketMessageAction::run($ticketMessage, $request->validated());

        return Response::data(
            TicketMessageResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('ticketMessage.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/ticket-message/{ticketMessage}",
     *     operationId="deleteTicketMessage",
     *     tags={"TicketMessage"},
     *     summary="Delete existing ticketMessage",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="ticketMessage", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="ticket-message has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(TicketMessage $ticketMessage): JsonResponse
    {
        DeleteTicketMessageAction::run($ticketMessage);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('ticketMessage.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/ticket-message/toggle/{ticketMessage}",
    //     *     operationId="toggleTicketMessage",
    //     *     tags={"TicketMessage"},
    //     *     summary="Toggle TicketMessage",
    //     *     @OA\Parameter(name="ticketMessage", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="ticket-message has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/TicketMessageResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(TicketMessage $ticketMessage): JsonResponse
    //    {
    //        $this->authorize('update', $ticketMessage);
    //        $ticketMessage = ToggleTicketMessageAction::run($ticketMessage);
    //
    //        return Response::data(
    //            TicketMessageResource::make($ticketMessage),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('ticketMessage.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/ticket-message/data",
    //     *     operationId="getTicketMessageData",
    //     *     tags={"TicketMessage"},
    //     *     summary="Get TicketMessage data",
    //     *     description="Returns TicketMessage data",
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
    //        $this->authorize('create', TicketMessage::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
