<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Banner\DeleteBannerAction;
use App\Actions\Banner\StoreBannerAction;
use App\Actions\Banner\UpdateBannerAction;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerDetailResource;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Repositories\Banner\BannerRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Banner::class);
    }

    /**
     * @OA\Get(
     *     path="/banner",
     *     operationId="getBanners",
     *     tags={"Banner"},
     *     summary="get banners list",
     *     description="Returns list of banners",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BannerResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/banner?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/banner?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/banner?page=2", nullable=true),
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
    public function index(BannerRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            BannerResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Banner::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/banner/{banner}",
     *     operationId="getBannerByID",
     *     tags={"Banner"},
     *     summary="Get banner information",
     *     description="Returns banner data",
     *     @OA\Parameter(name="banner", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/BannerDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Banner $banner): JsonResponse
    {
        return Response::data(
            BannerDetailResource::make($banner),
        );
    }

    /**
     * @OA\Post(
     *     path="/banner",
     *     operationId="storeBanner",
     *     tags={"Banner"},
     *     summary="Store new banner",
     *     description="Returns new banner data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreBannerRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreBannerRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="banner has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/BannerResource")
     *         )
     *     )
     * )
     */
    public function store(StoreBannerRequest $request): JsonResponse
    {
        $model = StoreBannerAction::run($request->validated());

        return Response::data(
            BannerResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('banner.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/banner/{banner}",
     *     operationId="updateBanner",
     *     tags={"Banner"},
     *     summary="Update existing banner",
     *     description="Returns updated banner data",
     *     @OA\Parameter(name="banner", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateBannerRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateBannerRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="banner has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/BannerResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateBannerRequest $request, Banner $banner): JsonResponse
    {
        $data = UpdateBannerAction::run($banner, $request->validated());

        return Response::data(
            BannerResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('banner.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/banner/{banner}",
     *     operationId="deleteBanner",
     *     tags={"Banner"},
     *     summary="Delete existing banner",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="banner", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="banner has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Banner $banner): JsonResponse
    {
        DeleteBannerAction::run($banner);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('banner.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/banner/toggle/{banner}",
    //     *     operationId="toggleBanner",
    //     *     tags={"Banner"},
    //     *     summary="Toggle Banner",
    //     *     @OA\Parameter(name="banner", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="banner has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/BannerResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(Banner $banner): JsonResponse
    //    {
    //        $this->authorize('update', $banner);
    //        $banner = ToggleBannerAction::run($banner);
    //
    //        return Response::data(
    //            BannerResource::make($banner),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('banner.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/banner/data",
    //     *     operationId="getBannerData",
    //     *     tags={"Banner"},
    //     *     summary="Get Banner data",
    //     *     description="Returns Banner data",
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
    //        $this->authorize('create', Banner::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
