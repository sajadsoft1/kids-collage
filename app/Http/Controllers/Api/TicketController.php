<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Ticket\DataTicketAction;
use App\Actions\Ticket\DeleteTicketAction;
use App\Actions\Ticket\StoreTicketAction;
use App\Actions\Ticket\ToggleTicketStatusAction;
use App\Actions\Ticket\UpdateTicketAction;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketDetailResource;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Ticket::class);
    }

    /**
     * @OA\Get(
     *     path="/ticket",
     *     operationId="getTickets",
     *     tags={"Ticket"},
     *     summary="get tickets list",
     *     description="Returns list of tickets",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TicketResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/ticket?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/ticket?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/ticket?page=2", nullable=true),
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
    public function index(TicketRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            TicketResource::collection($repository->paginate()), // user is loaded by default in repository
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Ticket::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/ticket/{ticket}",
     *     operationId="getTicketByID",
     *     tags={"Ticket"},
     *     summary="Get ticket information",
     *     description="Returns ticket data",
     *     @OA\Parameter(name="ticket", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/TicketDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Ticket $ticket): JsonResponse
    {
        $ticket->loadMissing(['closeBy', 'messages']); // user loaded by default

        return Response::data(
            TicketDetailResource::make($ticket),
        );
    }

    /**
     * @OA\Post(
     *     path="/ticket",
     *     operationId="storeTicket",
     *     tags={"Ticket"},
     *     summary="Store new ticket",
     *     description="Returns new ticket data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTicketRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreTicketRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="ticket has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/TicketResource")
     *         )
     *     )
     * )
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $model = StoreTicketAction::run($request->validated());

        return Response::data(
            TicketResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('ticket.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/ticket/{ticket}",
     *     operationId="updateTicket",
     *     tags={"Ticket"},
     *     summary="Update existing ticket",
     *     description="Returns updated ticket data",
     *     @OA\Parameter(name="ticket", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTicketRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateTicketRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="ticket has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/TicketResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): JsonResponse
    {
        $data = UpdateTicketAction::run($ticket, $request->validated());

        return Response::data(
            TicketResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('ticket.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/ticket/{ticket}",
     *     operationId="deleteTicket",
     *     tags={"Ticket"},
     *     summary="Delete existing ticket",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="ticket", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="ticket has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        DeleteTicketAction::run($ticket);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('ticket.model')]),
            Response::HTTP_OK
        );
    }

        /**
         * @OA\Post(
         *     path="/ticket/toggle/{ticket}",
         *     operationId="toggleTicket",
         *     tags={"Ticket"},
         *     summary="Toggle Ticket",
         *     @OA\Parameter(name="ticket", required=true, in="path", @OA\Schema(type="integer")),
         *     @OA\Response(response=200,
         *         description="Successful operation",
         *         @OA\JsonContent(type="object",
         *             @OA\Property(property="message", type="string", default="ticket has been toggled successfully"),
         *             @OA\Property(property="data", type="object", ref="#/components/schemas/TicketResource")
         *         )
         *     )
         * )
         */
        public function toggle(Ticket $ticket): JsonResponse
        {
            $this->authorize('update', $ticket);
            $ticket = ToggleTicketStatusAction::run($ticket);

            return Response::data(
                TicketResource::make($ticket),
                trans('general.model_has_toggled_successfully', ['model' => trans('ticket.model')]),
                Response::HTTP_OK
            );
        }

        /**
         * @OA\Get(
         *     path="/ticket/data",
         *     operationId="getTicketData",
         *     tags={"Ticket"},
         *     summary="Get Ticket data",
         *     description="Returns Ticket data",
         *     @OA\Response(response=200,
         *         description="Successful operation",
         *         @OA\JsonContent(type="object",
         *             @OA\Property(property="message", type="string", default="No message"),
         *             @OA\Property(property="data", type="object",
         *                 @OA\Property(property="user", ref="#/components/schemas/UserResource")
         *             )
         *         )
         *     )
         * )
         */
        public function extraData(Request $request): JsonResponse
        {
            $this->authorize('create', Ticket::class);
            return Response::data(
             DataTicketAction::run($request->all())
            );
        }
}
