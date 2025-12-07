<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Comment\StoreCommentAction;
use App\Filters\DateFilter;
use App\Filters\FuzzyFilter;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BulletinDetailResource;
use App\Http\Resources\BulletinResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use App\Models\Banner;
use App\Models\Bulletin;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Sorts\MostCommentSort;
use App\Sorts\MostWishSort;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Enums\SortDirection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Tags\Tag as SpatieTag;
use Throwable;

class BulletinController extends Controller
{
    public function __construct() {}

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Bulletin::query())
            ->with(['category'])
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->when($categoryId = Arr::get($payload, 'category_id'), fn ($q) => $q->where('category_id', $categoryId))
            ->when($tag = Arr::get($payload, 'tag'), fn ($q) => $q->withAnyTags([$tag->name], $tag->type))
            ->when($userId = Arr::get($payload, 'user_id'), fn ($q) => $q->where('user_id', $userId))
            ->where('published', true)
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
                AllowedSort::custom('view', new MostCommentSort)->defaultDirection(SortDirection::DESCENDING),
                AllowedSort::custom('comment', new MostCommentSort)->defaultDirection(SortDirection::DESCENDING),
                AllowedSort::custom('wish', new MostWishSort)->defaultDirection(SortDirection::DESCENDING),
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
                AllowedFilter::custom('date', new DateFilter),
            ]);
    }

    /**
     * @OA\Get(
     *     path="/bulletin",
     *     operationId="getBulletins",
     *     tags={"Bulletin"},
     *     summary="get bulletin list",
     *     description="Returns list of bulletin",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BulletinResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/bulletin?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/bulletin?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/bulletin?page=2", nullable=true),
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
     *
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
            ])->paginate($request->input('page_limit', 15))->toResourceCollection(BulletinResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id'],
                    ['label' => 'Most View', 'value' => 'view'],
                    ['label' => 'Most Comment', 'value' => 'comment'],
                    ['label' => 'Most Wish', 'value' => 'wish'],
                ],
                'filter' => [
                    'date' => [
                        ['label' => 'Last Week', 'value' => 'last_week'],
                        ['label' => 'Last Month', 'value' => 'last_month'],
                        ['label' => 'Last Year', 'value' => 'last_year'],
                    ],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/bulletin/index-extra-data",
     *     operationId="getIndexExtraData",
     *     tags={"Bulletin"},
     *     summary="Get index Bulletin Extera information",
     *     description="Returns index extrea data",
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object"
     *         )
     *     )
     * )
     */
    public function indexExtraData()
    {
        $latestBulletin = Bulletin::latestBulletin();
        $banners = Banner::latestBanner();
        $categories = Category::bulletinCategories();
        $tags = Tag::bulletinTags();

        return Response::data([
            'banners' => BannerResource::collection($banners),
            'latestBulletin' => BulletinResource::collection($latestBulletin),
            'Categories' => CategoryResource::collection($categories),
            'tags' => TagResource::collection($tags),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/bulletin/{bulletin}",
     *     operationId="getBulletinByID",
     *     tags={"Bulletin"},
     *     summary="Get bulletin information",
     *     description="Returns bulletin data",
     *     @OA\Parameter(name="bulletin", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     */
    public function show(Bulletin $bulletin): JsonResponse
    {
        return Response::data(
            [
                'bulletin' => BulletinDetailResource::make($bulletin->load('category', 'tags', 'user', 'comments')),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/bulletin/{bulletin}/show-data",
     *     operationId="getBulletinBySlug",
     *     tags={"Bulletin"},
     *     summary="Get bulletin extra information",
     *     description="Returns extra data",
     *     @OA\Parameter(name="bulletin", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data")
     *         )
     *     )
     * )
     */
    public function extraShowData(Bulletin $bulletin)
    {
        $relatedBulletins = $bulletin->relatedBulletin($bulletin);
        $banners = Banner::latestBanner();

        return Response::data([
            'banners' => BannerResource::collection($banners),
            'relatedBulletins' => BulletinResource::collection($relatedBulletins),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/bulletin/category/{category}",
     *     operationId="getBulletinsByCategory",
     *     tags={"Bulletin"},
     *     summary="get Bulletin list",
     *     description="Returns list of Bulletin",
     *     @OA\Parameter(name="category", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BulletinResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/bulletin?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/bulletin?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/bulletin?page=2", nullable=true),
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
                'limit' => $request->input('limit', 1),
                'category_id' => $category->id,
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(BulletinResource::class),
        );
    }

    /**
     * @OA\Get(
     *     path="/bulletin/tag/{tag}",
     *     operationId="getBulletinsByTag",
     *     tags={"Bulletin"},
     *     summary="get bulletins list",
     *     description="Returns list of bulletins",
     *     @OA\Parameter(name="tag", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BulletinResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/bulletin?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/bulletin?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/bulletin?page=2", nullable=true),
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
    public function indexByTag(Request $request, string $value): JsonResponse
    {
        $locale = app()->getLocale();
        $tag = SpatieTag::where("slug->{$locale}", $value)->firstOrFail();

        return Response::dataWithAdditional(
            $this->query([
                'limit' => $request->input('limit', 1),
                'tag' => $tag,
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(BulletinResource::class),
        );
    }

    /**
     * @OA\Get(
     *     path="/bulletin/author/{user}",
     *     operationId="getBulletinsUser",
     *     tags={"Bulletin"},
     *     summary="get bulletins list",
     *     description="Returns list of bulletins",
     *     @OA\Parameter(name="user", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BulletinResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/bulletin?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/bulletin?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/bulletin?page=2", nullable=true),
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
    public function indexByUser(Request $request, User $user): JsonResponse
    {
        return Response::dataWithAdditional(
            $this->query([
                'limit' => $request->input('limit', 1),
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(BulletinResource::class),
        );
    }

    /**
     * @OA\Post(
     *     path="/bulletin/{bulletin}/comment",
     *     operationId="storeCommentBulletin",
     *     tags={"Bulletin"},
     *     summary="Store bulletin comment",
     *     description="Store comment",
     *     @OA\Parameter(name="bulletin", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function storeUserComment(StoreCommentRequest $request, Bulletin $bulletin)
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['published'] = false;
        $data['morphable_type'] = Bulletin::class;
        $data['morphable_id'] = $bulletin->id;
        StoreCommentAction::run($data);

        return Response::data(
            [],
            trans('comment.store_successfully')
        );
    }
}
