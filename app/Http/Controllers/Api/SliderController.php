<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Slider\DeleteSliderAction;
use App\Actions\Slider\StoreSliderAction;
use App\Actions\Slider\UpdateSliderAction;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Http\Resources\SliderDetailResource;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use App\Repositories\Slider\SliderRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Slider::class);
    }

    /**
     * @OA\Get(
     *     path="/slider",
     *     operationId="getSliders",
     *     tags={"Slider"},
     *     summary="get sliders list",
     *     description="Returns list of sliders",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SliderResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/slider?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/slider?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/slider?page=2", nullable=true),
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
    public function index(SliderRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            SliderResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Slider::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/slider/{slider}",
     *     operationId="getSliderByID",
     *     tags={"Slider"},
     *     summary="Get slider information",
     *     description="Returns slider data",
     *     @OA\Parameter(name="slider", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/SliderDetailResource")
     *         )
     *     )
     * )
     */
    public function show(Slider $slider): JsonResponse
    {
        return Response::data(
            SliderDetailResource::make($slider),
        );
    }

    /**
     * @OA\Post(
     *     path="/slider",
     *     operationId="storeSlider",
     *     tags={"Slider"},
     *     summary="Store new slider",
     *     description="Returns new slider data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreSliderRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreSliderRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="slider has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/SliderResource")
     *         )
     *     )
     * )
     */
    public function store(StoreSliderRequest $request): JsonResponse
    {
        $model = StoreSliderAction::run($request->validated());

        return Response::data(
            SliderResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('slider.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/slider/{slider}",
     *     operationId="updateSlider",
     *     tags={"Slider"},
     *     summary="Update existing slider",
     *     description="Returns updated slider data",
     *     @OA\Parameter(name="slider", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSliderRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateSliderRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="slider has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/SliderResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateSliderRequest $request, Slider $slider): JsonResponse
    {
        $data = UpdateSliderAction::run($slider, $request->validated());

        return Response::data(
            SliderResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('slider.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/slider/{slider}",
     *     operationId="deleteSlider",
     *     tags={"Slider"},
     *     summary="Delete existing slider",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="slider", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="slider has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(Slider $slider): JsonResponse
    {
        DeleteSliderAction::run($slider);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('slider.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/slider/toggle/{slider}",
    //     *     operationId="toggleSlider",
    //     *     tags={"Slider"},
    //     *     summary="Toggle Slider",
    //     *     @OA\Parameter(name="slider", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="slider has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/SliderResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(Slider $slider): JsonResponse
    //    {
    //        $this->authorize('update', $slider);
    //        $slider = ToggleSliderAction::run($slider);
    //
    //        return Response::data(
    //            SliderResource::make($slider),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('slider.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/slider/data",
    //     *     operationId="getSliderData",
    //     *     tags={"Slider"},
    //     *     summary="Get Slider data",
    //     *     description="Returns Slider data",
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
    //        $this->authorize('create', Slider::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
