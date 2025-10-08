<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\ClientDetailResource;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class ClientController extends Controller
{
    public function __construct() {}

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Client::query())
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->where('published', true)
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title']])),
            ]);
    }

    /**
     * @OA\Get(
     *     path="/client",
     *     operationId="getClient",
     *     tags={"Client"},
     *     summary="get clients list",
     *     description="Returns list of clients",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
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
     *                 @OA\Property(property="sorts", type="array", @OA\Items(type="object"),
     *                     @OA\Property(property="label", type="string", default="ID"),
     *                     @OA\Property(property="value", type="string", default="id"),
     *                     @OA\Property(property="selected", type="boolean", default=true),
     *                     @OA\Property(property="default", type="boolean", default=true),
     *                 ),
     *             ),
     *         )
     *     )
     * )
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        return Response::dataWithAdditional(
            $this->query([
                'limit' => $request->input('limit', 1),
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(ClientResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
                ],
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
     *     @OA\Parameter(name="client", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     */
    public function show(Client $client): JsonResponse
    {
        return Response::data(
            [
                'client' => ClientDetailResource::make($client),
            ]
        );
    }
}
