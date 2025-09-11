<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Portfolio\DeletePortfolioAction;
use App\Actions\Portfolio\StorePortfolioAction;
use App\Actions\Portfolio\UpdatePortfolioAction;
use App\Http\Requests\StorePortfolioRequest;
use App\Http\Requests\UpdatePortfolioRequest;
use App\Http\Resources\PortfolioDetailResource;
use App\Http\Resources\PortfolioResource;
use App\Models\Portfolio;
use App\Repositories\Portfolio\PortfolioRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class PortfolioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Portfolio::class);
    }

    /**
     * @OA\Get(
     *     path="/portfolio",
     *     operationId="getPortfolios",
     *     tags={"Portfolio"},
     *     summary="get portfolios list",
     *     description="Returns list of portfolios",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PortfolioResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/portfolio?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/portfolio?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/portfolio?page=2", nullable=true),
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
    public function index(PortfolioRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            PortfolioResource::collection($repository->paginate(payload: [
                'with' => ['tags'], // category and creator are loaded by default in repository
            ])),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Portfolio::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/portfolio/{portfolio}",
     *     operationId="getPortfolioByID",
     *     tags={"Portfolio"},
     *     summary="Get portfolio information",
     *     description="Returns portfolio data",
     *     @OA\Parameter(name="portfolio", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/PortfolioDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Portfolio $portfolio): JsonResponse
    {
        $portfolio->loadMissing(['tags', 'seoOption']); // category and creator loaded by default
        
        return Response::data(
            PortfolioDetailResource::make($portfolio),
        );
    }

    /**
     * @OA\Post(
     *     path="/portfolio",
     *     operationId="storePortfolio",
     *     tags={"Portfolio"},
     *     summary="Store new portfolio",
     *     description="Returns new portfolio data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePortfolioRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StorePortfolioRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="portfolio has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PortfolioResource")
     *         )
     *     )
     * )
     */
    public function store(StorePortfolioRequest $request): JsonResponse
    {
        $model = StorePortfolioAction::run($request->validated());

        return Response::data(
            PortfolioResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('portfolio.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/portfolio/{portfolio}",
     *     operationId="updatePortfolio",
     *     tags={"Portfolio"},
     *     summary="Update existing portfolio",
     *     description="Returns updated portfolio data",
     *     @OA\Parameter(name="portfolio", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePortfolioRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdatePortfolioRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="portfolio has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PortfolioResource")
     *         )
     *     )
     * )
     */
    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio): JsonResponse
    {
        $data = UpdatePortfolioAction::run($portfolio, $request->validated());

        return Response::data(
            PortfolioResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('portfolio.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/portfolio/{portfolio}",
     *     operationId="deletePortfolio",
     *     tags={"Portfolio"},
     *     summary="Delete existing portfolio",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="portfolio", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="portfolio has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Portfolio $portfolio): JsonResponse
    {
        DeletePortfolioAction::run($portfolio);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('portfolio.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/portfolio/toggle/{portfolio}",
    //     *     operationId="togglePortfolio",
    //     *     tags={"Portfolio"},
    //     *     summary="Toggle Portfolio",
    //     *     @OA\Parameter(name="portfolio", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="portfolio has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/PortfolioResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(Portfolio $portfolio): JsonResponse
    //    {
    //        $this->authorize('update', $portfolio);
    //        $portfolio = TogglePortfolioAction::run($portfolio);
    //
    //        return Response::data(
    //            PortfolioResource::make($portfolio),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('portfolio.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/portfolio/data",
    //     *     operationId="getPortfolioData",
    //     *     tags={"Portfolio"},
    //     *     summary="Get Portfolio data",
    //     *     description="Returns Portfolio data",
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
    //        $this->authorize('create', Portfolio::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
