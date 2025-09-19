<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\News\ToggleNewsAction;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Resources\NewsDetailResource;
use App\Http\Resources\NewsResource;
use App\Actions\News\StoreNewsAction;
use App\Actions\News\DeleteNewsAction;
use App\Actions\News\UpdateNewsAction;
use App\Repositories\News\NewsRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class NewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(News::class);
    }

    /**
     * @OA\Get(
     *     path="/news",
     *     operationId="getNewss",
     *     tags={"News"},
     *     summary="get newss list",
     *     description="Returns list of newss",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NewsResource")),
                         *             @OA\Property(property="links", type="object",
                         *                 @OA\Property(property="first", type="string", default="http://localhost/api/news?page=1"),
                         *                 @OA\Property(property="last", type="string", default="http://localhost/api/news?page=4"),
                         *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
                         *                 @OA\Property(property="next", type="string", default="http://localhost/api/news?page=2", nullable=true),
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
     *          )
     *     )
     *     )
     */
    public function index(NewsRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            NewsResource::collection($repository->paginate(payload: [
                'with' => ['branch'],
            ])),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(News::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/news/{news}",
     *     operationId="getNewsByID",
     *     tags={"News"},
     *     summary="Get news information",
     *     description="Returns news data",
     *     @OA\Parameter(name="news", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/NewsDetailResource")
     *          )
     *      )
     * )
     */
    public function show(News $news): JsonResponse
    {
        return Response::data(
            NewsDetailResource::make($news),
        );
    }

    /**
     * @OA\Post(
     *     path="/news",
     *     operationId="storeNews",
     *     tags={"News"},
     *     summary="Store new news",
     *     description="Returns new news data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreNewsRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreNewsRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="news has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/NewsResource")
     *          )
     *      )
     * )
     */
    public function store(StoreNewsRequest $request): JsonResponse
    {
        $model = StoreNewsAction::run($request->validated());
        return Response::data(
            NewsResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('news.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/news/{news}",
     *     operationId="updateNews",
     *     tags={"News"},
     *     summary="Update existing news",
     *     description="Returns updated news data",
     *     @OA\Parameter(name="news",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateNewsRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateNewsRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="news has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/NewsResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateNewsRequest $request, News $news): JsonResponse
    {
        $data = UpdateNewsAction::run($news, $request->validated());
        return Response::data(
            NewsResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('news.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/news/{news}",
     *      operationId="deleteNews",
     *      tags={"News"},
     *      summary="Delete existing news",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="news",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="news has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(News $news): JsonResponse
    {
        DeleteNewsAction::run($news);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('news.model')]),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *     path="/news/toggle/{news}",
     *     operationId="toggleNews",
     *     tags={"News"},
     *     summary="Toggle News",
     *     @OA\Parameter(name="news", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="news has been toggled successfully"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/NewsResource")
     *         )
     *     )
     * )
     */
    public function toggle(News $news): JsonResponse
    {
        $this->authorize('update', $news);
        $news = ToggleNewsAction::run($news);

        return Response::data(
            NewsResource::make($news),
            trans('general.model_has_toggled_successfully', ['model' => trans('news.model')]),
            Response::HTTP_OK
        );
    }

//    /**
//     * @OA\Get(
//     *     path="/news/data",
//     *     operationId="getNewsData",
//     *     tags={"News"},
//     *     summary="Get News data",
//     *     description="Returns News data",
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
//        $this->authorize('create', News::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
