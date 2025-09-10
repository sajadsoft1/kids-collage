<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Blog\DataBlogAction;
use App\Actions\Blog\DeleteBlogAction;
use App\Actions\Blog\StoreBlogAction;
use App\Actions\Blog\ToggleBlogAction;
use App\Actions\Blog\UpdateBlogAction;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Repositories\Blog\BlogRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

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
    public function index(BlogRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            BlogResource::collection($repository->paginate(payload: [
                'with' => ['user', 'category'],
            ])),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Blog::class),
                'extra'                => $repository->extra(),
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
            BlogDetailResource::make($blog),
        );
    }

    /**
     * @OA\Post(
     *     path="/blog",
     *     operationId="storeBlog",
     *     tags={"Blog"},
     *     summary="Store new blog",
     *     description="Returns new blog data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreBlogRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreBlogRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="blog has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogResource")
     *         )
     *     )
     * )
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $model = StoreBlogAction::run($request->validated());

        return Response::data(
            BlogResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('blog.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/blog/{blog}",
     *     operationId="updateBlog",
     *     tags={"Blog"},
     *     summary="Update existing blog",
     *     description="Returns updated blog data",
     *     @OA\Parameter(name="blog", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateBlogRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateBlogRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="blog has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/BlogResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        $data = UpdateBlogAction::run($blog, $request->validated());

        return Response::data(
            BlogResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('blog.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/blog/{blog}",
     *     operationId="deleteBlog",
     *     tags={"Blog"},
     *     summary="Delete existing blog",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="blog", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="blog has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Blog $blog): JsonResponse
    {
        DeleteBlogAction::run($blog);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('blog.model')]),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *     path="/blog/toggle/{blog}",
     *     operationId="toggleBlog",
     *     tags={"Blog"},
     *     summary="Toggle Blog",
     *     @OA\Parameter(name="blog", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="blog has been toggled successfully"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/BlogResource")
     *         )
     *     )
     * )
     */
    public function toggle(Blog $blog): JsonResponse
    {
        $this->authorize('update', $blog);
        $blog = ToggleBlogAction::run($blog);

        return Response::data(
            BlogResource::make($blog),
            trans('general.model_has_toggled_successfully', ['model' => trans('blog.model')]),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Get(
     *     path="/blog/data",
     *     operationId="getBlogData",
     *     tags={"Blog"},
     *     summary="Get Blog data",
     *     description="Returns Blog data",
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
        $this->authorize('create', Blog::class);

        return Response::data(DataBlogAction::run($request->all()));
    }
}
