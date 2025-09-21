<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Blog::class);
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
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
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
     */
    public function index(Request $request): JsonResponse
    {
        return Response::dataWithAdditional(
            QueryBuilder::for(Blog::query())
                ->with(['user', 'category'])
                ->when($request->input('limit'), fn ($q) => $q->limit($request->input('limit')))
                ->defaultSort('-id')
                ->allowedSorts(['id', 'created_at', 'updated_at'])
                ->allowedFilters([
                    AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
                ])
        );
    }

    /**
     * @OA\Get(
     *     path="/blog/{blog}",
     *     operationId="getBlogByID",
     *     tags={"Blog"},
     *     summary="Get blog information",
     *     description="Returns blog data",
     *     @OA\Parameter(name="blog", required=true, in="path", @OA\Schema(type="integer")),
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
        return Response::data(
            [
                'blog' => BlogDetailResource::make($blog->load(['user', 'category'])),
            ]
        );
    }
}
