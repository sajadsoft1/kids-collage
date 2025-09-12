<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Category\DataCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\StoreCategoryAction;
use App\Actions\Category\ToggleCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryDetailResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Category::class);
    }

    /**
     * @OA\Get(
     *     path="/category",
     *     operationId="getCategorys",
     *     tags={"Category"},
     *     summary="get categorys list",
     *     description="Returns list of categorys",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CategoryResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/category?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/category?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/category?page=2", nullable=true),
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
    public function index(CategoryRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            CategoryResource::collection($repository->paginate(payload: [
                'with' => ['children'], // parent is loaded by default in repository
            ])),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Category::class),
                'extra' => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/category/{category}",
     *     operationId="getCategoryByID",
     *     tags={"Category"},
     *     summary="Get category information",
     *     description="Returns category data",
     *     @OA\Parameter(name="category", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Category $category): JsonResponse
    {
        $category->loadMissing(['parent', 'children', 'seoOption']);

        return Response::data(
            CategoryDetailResource::make($category),
        );
    }

    /**
     * @OA\Post(
     *     path="/category",
     *     operationId="storeCategory",
     *     tags={"Category"},
     *     summary="Store new category",
     *     description="Returns new category data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCategoryRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreCategoryRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="category has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryResource")
     *         )
     *     )
     * )
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $model = StoreCategoryAction::run($request->validated());

        return Response::data(
            CategoryResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('category.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/category/{category}",
     *     operationId="updateCategory",
     *     tags={"Category"},
     *     summary="Update existing category",
     *     description="Returns updated category data",
     *     @OA\Parameter(name="category", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCategoryRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateCategoryRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="category has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $data = UpdateCategoryAction::run($category, $request->validated());

        return Response::data(
            CategoryResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('category.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/category/{category}",
     *     operationId="deleteCategory",
     *     tags={"Category"},
     *     summary="Delete existing category",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="category", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="category has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Category $category): JsonResponse
    {
        DeleteCategoryAction::run($category);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('category.model')]),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *     path="/category/toggle/{category}",
     *     operationId="toggleCategory",
     *     tags={"Category"},
     *     summary="Toggle Category",
     *     @OA\Parameter(name="category", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="category has been toggled successfully"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/CategoryResource")
     *         )
     *     )
     * )
     */
    public function toggle(Category $category): JsonResponse
    {
        $this->authorize('update', $category);
        $category = ToggleCategoryAction::run($category);

        return Response::data(
            CategoryResource::make($category),
            trans('general.model_has_toggled_successfully', ['model' => trans('category.model')]),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Get(
     *     path="/category/data",
     *     operationId="getCategoryData",
     *     tags={"Category"},
     *     summary="Get Category data",
     *     description="Returns Category data",
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
        $this->authorize('create', Category::class);
        return Response::data(
           DataCategoryAction::run($request->all())
        );
    }
}
