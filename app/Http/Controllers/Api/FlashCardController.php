<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\FlashCardDetailResource;
use App\Http\Resources\FlashCardResource;
use App\Models\FlashCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class FlashCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(FlashCard::query())
            ->with('user')
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->where('user_id', auth()->user()->id)
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
            ]);
    }

    /**
     * @OA\Get(
     *     path="/flash-card",
     *     operationId="getFlashCards",
     *     tags={"FlashCard"},
     *     summary="get flashCard list",
     *     description="Returns list of flashCard",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/FlashCardResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/flash-card?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/flash-card?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/flash-card?page=2", nullable=true),
     *             ),
     *             @OA\Property(property="meta", ref="#/components/schemas/Meta"),
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="extra", type="object",
     *                 @OA\Property(property="default_sort", type="string", default="-id"),
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
                'limit' => $request->input('limit'),
            ])->paginate($request->input('page_limit', 15))->toResourceCollection(FlashCardResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/flash-card/{flashCard}",
     *     operationId="getFlashCardByID",
     *     tags={"FlashCard"},
     *     summary="Get flashCard information",
     *     description="Returns flashCard data",
     *     @OA\Parameter(name="flashCard", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     */
    public function show(FlashCard $flashCard): JsonResponse
    {
        abort_if($flashCard->user_id !== auth()->user()->id, 404);

        return Response::data(
            [
                'flashCard' => FlashCardDetailResource::make($flashCard->load('leitnerLogs')),
            ]
        );
    }
}
