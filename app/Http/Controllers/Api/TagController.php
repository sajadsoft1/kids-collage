<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Resources\TagDetailResource;
use App\Http\Resources\TagResource;
use App\Actions\Tag\StoreTagAction;
use App\Actions\Tag\DeleteTagAction;
use App\Actions\Tag\UpdateTagAction;
use App\Repositories\Tag\TagRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Tag::class);
    }

    /**
     * @OA\Get(
     *     path="/tag",
     *     operationId="getTags",
     *     tags={"Tag"},
     *     summary="get tags list",
     *     description="Returns list of tags",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TagResource")),
                         *             @OA\Property(property="links", type="object",
                         *                 @OA\Property(property="first", type="string", default="http://localhost/api/tag?page=1"),
                         *                 @OA\Property(property="last", type="string", default="http://localhost/api/tag?page=4"),
                         *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
                         *                 @OA\Property(property="next", type="string", default="http://localhost/api/tag?page=2", nullable=true),
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
    public function index(TagRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            TagResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Tag::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/tag/{tag}",
     *     operationId="getTagByID",
     *     tags={"Tag"},
     *     summary="Get tag information",
     *     description="Returns tag data",
     *     @OA\Parameter(name="tag", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/TagDetailResource")
     *          )
     *      )
     * )
     */
    public function show(Tag $tag): JsonResponse
    {
        return Response::data(
            TagDetailResource::make($tag),
        );
    }

    /**
     * @OA\Post(
     *     path="/tag",
     *     operationId="storeTag",
     *     tags={"Tag"},
     *     summary="Store new tag",
     *     description="Returns new tag data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreTagRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreTagRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="tag has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/TagResource")
     *          )
     *      )
     * )
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $model = StoreTagAction::run($request->validated());
        return Response::data(
            TagResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('tag.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/tag/{tag}",
     *     operationId="updateTag",
     *     tags={"Tag"},
     *     summary="Update existing tag",
     *     description="Returns updated tag data",
     *     @OA\Parameter(name="tag",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTagRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateTagRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="tag has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/TagResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $data = UpdateTagAction::run($tag, $request->validated());
        return Response::data(
            TagResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('tag.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/tag/{tag}",
     *      operationId="deleteTag",
     *      tags={"Tag"},
     *      summary="Delete existing tag",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="tag",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="tag has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(Tag $tag): JsonResponse
    {
        DeleteTagAction::run($tag);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('tag.model')]),
            Response::HTTP_OK
        );
    }

//    /**
//     * @OA\Post(
//     *     path="/tag/toggle/{tag}",
//     *     operationId="toggleTag",
//     *     tags={"Tag"},
//     *     summary="Toggle Tag",
//     *     @OA\Parameter(name="tag", required=true, in="path", @OA\Schema(type="integer")),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="tag has been toggled successfully"),
//     *             @OA\Property(property="data", type="object", ref="#/components/schemas/TagResource")
//     *         )
//     *     )
//     * )
//     */
//    public function toggle(Tag $tag): JsonResponse
//    {
//        $this->authorize('update', $tag);
//        $tag = ToggleTagAction::run($tag);
//
//        return Response::data(
//            TagResource::make($tag),
//            trans('general.model_has_toggled_successfully', ['model' => trans('tag.model')]),
//            Response::HTTP_OK
//        );
//    }
//
//    /**
//     * @OA\Get(
//     *     path="/tag/data",
//     *     operationId="getTagData",
//     *     tags={"Tag"},
//     *     summary="Get Tag data",
//     *     description="Returns Tag data",
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
//        $this->authorize('create', Tag::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
