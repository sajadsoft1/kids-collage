<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\ContactUs\DeleteContactUsAction;
use App\Actions\ContactUs\StoreContactUsAction;
use App\Actions\ContactUs\UpdateContactUsAction;
use App\Http\Requests\StoreContactUsRequest;
use App\Http\Requests\UpdateContactUsRequest;
use App\Http\Resources\ContactUsDetailResource;
use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use App\Repositories\ContactUs\ContactUsRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ContactUsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(ContactUs::class);
    }

    /**
     * @OA\Get(
     *     path="/contact-us",
     *     operationId="getContactUss",
     *     tags={"ContactUs"},
     *     summary="get contactUss list",
     *     description="Returns list of contactUss",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ContactUsResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/contact-us?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/contact-us?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/contact-us?page=2", nullable=true),
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
    public function index(ContactUsRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            ContactUsResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(ContactUs::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/contact-us/{contactUs}",
     *     operationId="getContactUsByID",
     *     tags={"ContactUs"},
     *     summary="Get contactUs information",
     *     description="Returns contactUs data",
     *     @OA\Parameter(name="contactUs", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/ContactUsDetailResource")
     *         )
     *     )
     * )
     */
    public function show(ContactUs $contactUs): JsonResponse
    {
        return Response::data(
            ContactUsDetailResource::make($contactUs),
        );
    }

    /**
     * @OA\Post(
     *     path="/contact-us",
     *     operationId="storeContactUs",
     *     tags={"ContactUs"},
     *     summary="Store new contactUs",
     *     description="Returns new contactUs data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreContactUsRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreContactUsRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="contact-us has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ContactUsResource")
     *         )
     *     )
     * )
     */
    public function store(StoreContactUsRequest $request): JsonResponse
    {
        $model = StoreContactUsAction::run($request->validated());

        return Response::data(
            ContactUsResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('contactUs.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/contact-us/{contactUs}",
     *     operationId="updateContactUs",
     *     tags={"ContactUs"},
     *     summary="Update existing contactUs",
     *     description="Returns updated contactUs data",
     *     @OA\Parameter(name="contactUs", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateContactUsRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateContactUsRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="contact-us has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ContactUsResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateContactUsRequest $request, ContactUs $contactUs): JsonResponse
    {
        $data = UpdateContactUsAction::run($contactUs, $request->validated());

        return Response::data(
            ContactUsResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('contactUs.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/contact-us/{contactUs}",
     *     operationId="deleteContactUs",
     *     tags={"ContactUs"},
     *     summary="Delete existing contactUs",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="contactUs", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="contact-us has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(ContactUs $contactUs): JsonResponse
    {
        DeleteContactUsAction::run($contactUs);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('contactUs.model')]),
            Response::HTTP_OK
        );
    }

    //    /**
    //     * @OA\Post(
    //     *     path="/contact-us/toggle/{contactUs}",
    //     *     operationId="toggleContactUs",
    //     *     tags={"ContactUs"},
    //     *     summary="Toggle ContactUs",
    //     *     @OA\Parameter(name="contactUs", required=true, in="path", @OA\Schema(type="integer")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="contact-us has been toggled successfully"),
    //     *             @OA\Property(property="data", type="object", ref="#/components/schemas/ContactUsResource")
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function toggle(ContactUs $contactUs): JsonResponse
    //    {
    //        $this->authorize('update', $contactUs);
    //        $contactUs = ToggleContactUsAction::run($contactUs);
    //
    //        return Response::data(
    //            ContactUsResource::make($contactUs),
    //            trans('general.model_has_toggled_successfully', ['model' => trans('contactUs.model')]),
    //            Response::HTTP_OK
    //        );
    //    }
    //
    //    /**
    //     * @OA\Get(
    //     *     path="/contact-us/data",
    //     *     operationId="getContactUsData",
    //     *     tags={"ContactUs"},
    //     *     summary="Get ContactUs data",
    //     *     description="Returns ContactUs data",
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
    //        $this->authorize('create', ContactUs::class);
    //        return Response::data(
    //            [
    //                'user'  => $request->user()
    //            ]
    //        );
    //    }
}
