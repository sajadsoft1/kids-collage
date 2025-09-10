<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateFaqRequest;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Resources\FaqDetailResource;
use App\Http\Resources\FaqResource;
use App\Actions\Faq\StoreFaqAction;
use App\Actions\Faq\DeleteFaqAction;
use App\Actions\Faq\UpdateFaqAction;
use App\Repositories\Faq\FaqRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class FaqController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Faq::class);
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
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/FaqResource")),
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
     *          )
     *     )
     *     )
     */
    public function index(FaqRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            FaqResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Faq::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/faq/{faq}",
     *     operationId="getFaqByID",
     *     tags={"Faq"},
     *     summary="Get faq information",
     *     description="Returns faq data",
     *     @OA\Parameter(name="faq", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/FaqDetailResource")
     *          )
     *      )
     * )
     */
    public function show(Faq $faq): JsonResponse
    {
        return Response::data(
            FaqDetailResource::make($faq),
        );
    }

    /**
     * @OA\Post(
     *     path="/faq",
     *     operationId="storeFaq",
     *     tags={"Faq"},
     *     summary="Store new faq",
     *     description="Returns new faq data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreFaqRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreFaqRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="faq has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/FaqResource")
     *          )
     *      )
     * )
     */
    public function store(StoreFaqRequest $request): JsonResponse
    {
        $model = StoreFaqAction::run($request->validated());
        return Response::data(
            FaqResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('faq.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/faq/{faq}",
     *     operationId="updateFaq",
     *     tags={"Faq"},
     *     summary="Update existing faq",
     *     description="Returns updated faq data",
     *     @OA\Parameter(name="faq",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateFaqRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateFaqRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="faq has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/FaqResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateFaqRequest $request, Faq $faq): JsonResponse
    {
        $data = UpdateFaqAction::run($faq, $request->validated());
        return Response::data(
            FaqResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('faq.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/faq/{faq}",
     *      operationId="deleteFaq",
     *      tags={"Faq"},
     *      summary="Delete existing faq",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="faq",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="faq has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(Faq $faq): JsonResponse
    {
        DeleteFaqAction::run($faq);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('faq.model')]),
            Response::HTTP_OK
        );
    }

//    /**
//     * @OA\Post(
//     *     path="/faq/toggle/{faq}",
//     *     operationId="toggleFaq",
//     *     tags={"Faq"},
//     *     summary="Toggle Faq",
//     *     @OA\Parameter(name="faq", required=true, in="path", @OA\Schema(type="integer")),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="faq has been toggled successfully"),
//     *             @OA\Property(property="data", type="object", ref="#/components/schemas/FaqResource")
//     *         )
//     *     )
//     * )
//     */
//    public function toggle(Faq $faq): JsonResponse
//    {
//        $this->authorize('update', $faq);
//        $faq = ToggleFaqAction::run($faq);
//
//        return Response::data(
//            FaqResource::make($faq),
//            trans('general.model_has_toggled_successfully', ['model' => trans('faq.model')]),
//            Response::HTTP_OK
//        );
//    }
//
//    /**
//     * @OA\Get(
//     *     path="/faq/data",
//     *     operationId="getFaqData",
//     *     tags={"Faq"},
//     *     summary="Get Faq data",
//     *     description="Returns Faq data",
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
//        $this->authorize('create', Faq::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
