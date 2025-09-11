<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Client\DeleteClientAction;
use App\Actions\Client\StoreClientAction;
use App\Actions\Client\UpdateClientAction;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientDetailResource;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Repositories\Client\ClientRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Client::class);
    }

    /**
     * @OA\Get(
     *     path="/client",
     *     operationId="getClients",
     *     tags={"Client"},
     *     summary="get clients list",
     *     description="Returns list of clients",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ClientResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/client?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/client?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/client?page=2", nullable=true),
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
    public function index(ClientRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            ClientResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Client::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/client/{client}",
     *     operationId="getClientByID",
     *     tags={"Client"},
     *     summary="Get client information",
     *     description="Returns client data",
     *     @OA\Parameter(name="client", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/ClientDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Client $client): JsonResponse
    {
        return Response::data(
            ClientDetailResource::make($client),
        );
    }

    /**
     * @OA\Post(
     *     path="/client",
     *     operationId="storeClient",
     *     tags={"Client"},
     *     summary="Store new client",
     *     description="Returns new client data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreClientRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreClientRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="client has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ClientResource")
     *         )
     *     )
     * )
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $model = StoreClientAction::run($request->validated());

        return Response::data(
            ClientResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('client.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/client/{client}",
     *     operationId="updateClient",
     *     tags={"Client"},
     *     summary="Update existing client",
     *     description="Returns updated client data",
     *     @OA\Parameter(name="client", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateClientRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateClientRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="client has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ClientResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $data = UpdateClientAction::run($client, $request->validated());

        return Response::data(
            ClientResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('client.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/client/{client}",
     *     operationId="deleteClient",
     *     tags={"Client"},
     *     summary="Delete existing client",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="client", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="client has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Client $client): JsonResponse
    {
        DeleteClientAction::run($client);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('client.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/client/toggle/{client}",
    //     *     operationId="toggleClient",
    //     *     tags={"Client"},
    //     *     summary="Toggle Client",
    //     *     @OA\Parameter(name="client", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="client has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/ClientResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(Client $client): JsonResponse
    //    {
    //        $this->authorize('update', $client);
    //        $client = ToggleClientAction::run($client);
    //
    //        return Response::data(
    //            ClientResource::make($client),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('client.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/client/data",
    //     *     operationId="getClientData",
    //     *     tags={"Client"},
    //     *     summary="Get Client data",
    //     *     description="Returns Client data",
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
    //        $this->authorize('create', Client::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
