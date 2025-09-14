<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\SocialMedia\DataSocialMediaAction;
use App\Actions\SocialMedia\DeleteSocialMediaAction;
use App\Actions\SocialMedia\StoreSocialMediaAction;
use App\Actions\SocialMedia\ToggleSocialMediaAction;
use App\Actions\SocialMedia\UpdateSocialMediaAction;
use App\Http\Requests\StoreSocialMediaRequest;
use App\Http\Requests\UpdateSocialMediaRequest;
use App\Http\Resources\SocialMediaDetailResource;
use App\Http\Resources\SocialMediaResource;
use App\Models\SocialMedia;
use App\Repositories\SocialMedia\SocialMediaRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class SocialMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(SocialMedia::class);
    }

    /**
     * @OA\Get(
     *     path="/social-media",
     *     operationId="getSocialMedias",
     *     tags={"SocialMedia"},
     *     summary="get socialMedias list",
     *     description="Returns list of socialMedias",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SocialMediaResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/social-media?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/social-media?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/social-media?page=2", nullable=true),
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
    public function index(SocialMediaRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            SocialMediaResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(SocialMedia::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/social-media/{socialMedia}",
     *     operationId="getSocialMediaByID",
     *     tags={"SocialMedia"},
     *     summary="Get socialMedia information",
     *     description="Returns socialMedia data",
     *     @OA\Parameter(name="socialMedia", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="data", ref="#/components/schemas/SocialMediaDetailResource")
     *         )
     *     )
     * )
     */
    public function show(SocialMedia $socialMedia): JsonResponse
    {
        return Response::data(
            SocialMediaDetailResource::make($socialMedia),
        );
    }

    /**
     * @OA\Post(
     *     path="/social-media",
     *     operationId="storeSocialMedia",
     *     tags={"SocialMedia"},
     *     summary="Store new socialMedia",
     *     description="Returns new socialMedia data",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreSocialMediaRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/StoreSocialMediaRequest"))
     *     ),
     *     @OA\Response(response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="social-media has been stored successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/SocialMediaResource")
     *         )
     *     )
     * )
     */
    public function store(StoreSocialMediaRequest $request): JsonResponse
    {
        $model = StoreSocialMediaAction::run($request->validated());

        return Response::data(
            SocialMediaResource::make($model),
            trans('general.model_has_stored_successfully', ['model' => trans('socialMedia.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/social-media/{socialMedia}",
     *     operationId="updateSocialMedia",
     *     tags={"SocialMedia"},
     *     summary="Update existing socialMedia",
     *     description="Returns updated socialMedia data",
     *     @OA\Parameter(name="socialMedia", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSocialMediaRequest"),
     *         @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/UpdateSocialMediaRequest"))
     *     ),
     *     @OA\Response(response=202,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="social-media has been updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/SocialMediaResource")
     *         )
     *     )
     * )
     */
    public function update(UpdateSocialMediaRequest $request, SocialMedia $socialMedia): JsonResponse
    {
        $data = UpdateSocialMediaAction::run($socialMedia, $request->validated());

        return Response::data(
            SocialMediaResource::make($data),
            trans('general.model_has_updated_successfully', ['model' => trans('socialMedia.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *     path="/social-media/{socialMedia}",
     *     operationId="deleteSocialMedia",
     *     tags={"SocialMedia"},
     *     summary="Delete existing socialMedia",
     *     description="Deletes a record and returns no content",
     *     @OA\Parameter(name="socialMedia", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="boolean", default=true),
     *             @OA\Property(property="message", type="string", default="social-media has been deleted successfully")
     *         ),
     *     )
     * )
     */
    public function destroy(SocialMedia $socialMedia): JsonResponse
    {
        DeleteSocialMediaAction::run($socialMedia);

        return Response::data(
            true,
            trans('general.model_has_deleted_successfully', ['model' => trans('socialMedia.model')]),
            Response::HTTP_OK
        );
    }

        /**
         * @OA\Post(
         *     path="/social-media/toggle/{socialMedia}",
         *     operationId="toggleSocialMedia",
         *     tags={"SocialMedia"},
         *     summary="Toggle SocialMedia",
         *     @OA\Parameter(name="socialMedia", required=true, in="path", @OA\Schema(type="integer")),
         *     @OA\Response(response=200,
         *         description="Successful operation",
         *         @OA\JsonContent(type="object",
         *             @OA\Property(property="message", type="string", default="social-media has been toggled successfully"),
         *             @OA\Property(property="data", type="object", ref="#/components/schemas/SocialMediaResource")
         *         )
         *     )
         * )
         */
        public function toggle(SocialMedia $socialMedia): JsonResponse
        {
            $this->authorize('update', $socialMedia);
            $socialMedia = ToggleSocialMediaAction::run($socialMedia);

            return Response::data(
                SocialMediaResource::make($socialMedia),
                trans('general.model_has_toggled_successfully', ['model' => trans('socialMedia.model')]),
                Response::HTTP_OK
            );
        }

        /**
         * @OA\Get(
         *     path="/social-media/data",
         *     operationId="getSocialMediaData",
         *     tags={"SocialMedia"},
         *     summary="Get SocialMedia data",
         *     description="Returns SocialMedia data",
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
            $this->authorize('create', SocialMedia::class);
            return Response::data(
                DataSocialMediaAction::run($request->all())
            );
        }
}
