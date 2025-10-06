<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\DateFilter;
use App\Filters\FuzzyFilter;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use App\Models\Banner;
use App\Models\Blog;
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
use Throwable;

class BlogController extends Controller
{
    public function __construct()
    {
        //        $this->middleware('auth:sanctum');
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Blog::query())
            ->with(['user', 'category', 'media'])
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->when($categoryId = Arr::get($payload, 'category_id'), fn ($q) => $q->where('category_id', $categoryId))
            ->when($tagId = Arr::get($payload, 'tag_id'), fn ($q) => $q->withAnyTags([$tagId], 'tags'))
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
     *     path="/blog",
     *     operationId="getBlogs",
     *     tags={"Blog"},
     *     summary="get blogs list",
     *     description="Returns list of blogs",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Parameter(name="filter[date]", required=false, in="query", @OA\Schema(type="string", enum={"today", "this_week", "this_month", "this_year"}), description="Filter by date"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BlogResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/blog?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/blog?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/blog?page=2", nullable=true),
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
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(BlogResource::class),
            [
                'sort'   => [
                    ['label' => '', 'value' => 'id'],
                    ['label' => trans('blog.sort.most_view'), 'value' => 'view'],
                    ['label' => trans('blog.sort.most_comment'), 'value' => 'comment'],
                    ['label' => trans('blog.sort.most_wish'), 'value' => 'wish'],
                ],
                'filter' => [
                    'date' => [
                        ['label' => trans('blog.filter.last_week'), 'value' => 'this_week'],
                        ['label' => trans('blog.filter.last_month'), 'value' => 'this_month'],
                        ['label' => trans('blog.filter.last_year'), 'value' => 'this_year'],
                    ],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/blog/category/{category}",
     *     operationId="getBlogsByCategory",
     *     tags={"Blog"},
     *     summary="get blogs list",
     *     description="Returns list of blogs",
     *     @OA\Parameter(name="category", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BlogResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/blog?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/blog?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/blog?page=2", nullable=true),
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
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(BlogResource::class),
            [
                'aaa' => 'bbbb',
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/blog/tag/{tag}",
     *     operationId="getBlogsByTag",
     *     tags={"Blog"},
     *     summary="get blogs list",
     *     description="Returns list of blogs",
     *     @OA\Parameter(name="tag", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BlogResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/blog?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/blog?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/blog?page=2", nullable=true),
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
    public function indexByTag(Request $request, Tag $tag): JsonResponse
    {
        return Response::dataWithAdditional(
            $this->query([
                'limit' => $request->input('limit', 1),
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(BlogResource::class),
            [
                'aaa' => 'bbbb',
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/blog/author/{user}",
     *     operationId="getBlogsUser",
     *     tags={"Blog"},
     *     summary="get blogs list",
     *     description="Returns list of blogs",
     *     @OA\Parameter(name="user", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BlogResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/blog?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/blog?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/blog?page=2", nullable=true),
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
            ])->paginate($request->input('page_limit', 1))->toResourceCollection(BlogResource::class),
            [
                'aaa' => 'bbbb',
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/blog/{blog}",
     *     operationId="getBlogByID",
     *     tags={"Blog"},
     *     summary="Get blog information",
     *     description="Returns blog data",
     *     @OA\Parameter(name="blog", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Blog $blog): JsonResponse
    {
        $blog->recordView();

        return Response::data(
            [
                'blog' => BlogDetailResource::make($blog->load(['user', 'category', 'media', 'seoOption'])),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/blog/{blog}/data",
     *     operationId="getExtraData",
     *     tags={"Blog"},
     *     summary="Get blog Extera information",
     *     description="Returns extrea data",
     *     @OA\Parameter(name="blog", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object"
     *         )
     *     )
     * )
     */
    public function extraData(Blog $blog)
    {
        $relatedBlogs = $blog->relatedBlogs($blog);
        $banners      = Banner::latestBanner();
        $tags=Tag::blogTags();


        return Response::data([
            'banners'      => BannerResource::collection($banners),
            'relatedBlogs' => BlogResource::collection($relatedBlogs),
            'tags'=>TagResource::collection($tags)


        ]);
    }    /**
     * @OA\Get(
     *     path="/blog/data",
     *     operationId="getData",
     *     tags={"Blog"},
     *     summary="Get index blog Extera information",
     *     description="Returns index extrea data",
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object"
     *         )
     *     )
     * )
     */
    public function data()
    {
        $popularBlog = Blog::popularBlogs();
        $banners      = Banner::latestBanner();
        $categories=Category::BlogCategories();
        $tags=Tag::blogTags();

        return Response::data([
            'banners'      => BannerResource::collection($banners),
            'popularBlog' => BlogResource::collection($popularBlog),
            'Categories'=>CategoryResource::collection($categories),
            'tags'=>TagResource::collection($tags)

        ]);
    }
}
