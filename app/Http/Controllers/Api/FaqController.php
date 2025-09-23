<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\FaqDetailResource;
use App\Http\Resources\FaqResource;
use App\Models\Category;
use App\Models\Faq;
use App\Sorts\MostCommentSort;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Enums\SortDirection;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class FaqController extends Controller
{
    public function __construct()
    {
        //        $this->middleware('auth:sanctum');
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Faq::query())
            ->with(['category'])
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->when($categoryId = Arr::get($payload, 'category_id'), fn ($q) => $q->where('category_id', $categoryId))
            ->where('published', true)
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
                AllowedSort::custom('view', new MostCommentSort)->defaultDirection(SortDirection::DESCENDING),
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
            ]);
    }

    /**
     * @OA\Get(
     *     path="/faq",
     *     operationId="getFaqs",
     *     tags={"Faq"},
     *     summary="get faqs list",
     *     description="Returns list of faqs",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/FaqResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/faq?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/faq?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/faq?page=2", nullable=true),
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
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        return Response::dataWithAdditional(
            $this->query([
                'limit' => $request->input('limit', 1),
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(FaqResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id'],
                    ['label' => 'Most View', 'value' => 'view'],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/faq/category/{category}",
     *     operationId="getFaqsByCategory",
     *     tags={"Faq"},
     *     summary="get faqs list",
     *     description="Returns list of faqs",
     *     @OA\Parameter(name="category", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/FaqResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/faq?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/faq?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/faq?page=2", nullable=true),
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
     * @throws Throwable
     */
    public function indexByCategory(Request $request, Category $category): JsonResponse
    {
        return Response::dataWithAdditional(
            $this->query([
                'limit'       => $request->input('limit', 1),
                'category_id' => $category->id,
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(FaqResource::class),
        );
    }

    /**
     * @OA\Get(
     *     path="/faq/{faq}",
     *     operationId="getFaqByID",
     *     tags={"Faq"},
     *     summary="Get faq information",
     *     description="Returns faq data",
     *     @OA\Parameter(name="faq", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     */
    public function show(Faq $faq): JsonResponse
    {
        return Response::data(
            [
                'faq' => FaqDetailResource::make($faq->load(['category'])),
            ]
        );
    }
}
