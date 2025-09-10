<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentDetailResource;
use App\Http\Resources\CommentResource;
use App\Actions\Comment\StoreCommentAction;
use App\Actions\Comment\DeleteCommentAction;
use App\Actions\Comment\UpdateCommentAction;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Comment::class);
    }

    /**
     * @OA\Get(
     *     path="/comment",
     *     operationId="getComments",
     *     tags={"Comment"},
     *     summary="get comments list",
     *     description="Returns list of comments",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CommentResource")),
                         *             @OA\Property(property="links", type="object",
                         *                 @OA\Property(property="first", type="string", default="http://localhost/api/comment?page=1"),
                         *                 @OA\Property(property="last", type="string", default="http://localhost/api/comment?page=4"),
                         *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
                         *                 @OA\Property(property="next", type="string", default="http://localhost/api/comment?page=2", nullable=true),
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
    public function index(CommentRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            CommentResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Comment::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/comment/{comment}",
     *     operationId="getCommentByID",
     *     tags={"Comment"},
     *     summary="Get comment information",
     *     description="Returns comment data",
     *     @OA\Parameter(name="comment", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/CommentDetailResource")
     *          )
     *      )
     * )
     */
    public function show(Comment $comment): JsonResponse
    {
        return Response::data(
            CommentDetailResource::make($comment),
        );
    }

    /**
     * @OA\Post(
     *     path="/comment",
     *     operationId="storeComment",
     *     tags={"Comment"},
     *     summary="Store new comment",
     *     description="Returns new comment data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreCommentRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="comment has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/CommentResource")
     *          )
     *      )
     * )
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $model = StoreCommentAction::run($request->validated());
        return Response::data(
            CommentResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('comment.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/comment/{comment}",
     *     operationId="updateComment",
     *     tags={"Comment"},
     *     summary="Update existing comment",
     *     description="Returns updated comment data",
     *     @OA\Parameter(name="comment",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCommentRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateCommentRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="comment has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/CommentResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $data = UpdateCommentAction::run($comment, $request->validated());
        return Response::data(
            CommentResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('comment.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/comment/{comment}",
     *      operationId="deleteComment",
     *      tags={"Comment"},
     *      summary="Delete existing comment",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="comment",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="comment has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(Comment $comment): JsonResponse
    {
        DeleteCommentAction::run($comment);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('comment.model')]),
            Response::HTTP_OK
        );
    }

//    /**
//     * @OA\Post(
//     *     path="/comment/toggle/{comment}",
//     *     operationId="toggleComment",
//     *     tags={"Comment"},
//     *     summary="Toggle Comment",
//     *     @OA\Parameter(name="comment", required=true, in="path", @OA\Schema(type="integer")),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="comment has been toggled successfully"),
//     *             @OA\Property(property="data", type="object", ref="#/components/schemas/CommentResource")
//     *         )
//     *     )
//     * )
//     */
//    public function toggle(Comment $comment): JsonResponse
//    {
//        $this->authorize('update', $comment);
//        $comment = ToggleCommentAction::run($comment);
//
//        return Response::data(
//            CommentResource::make($comment),
//            trans('general.model_has_toggled_successfully', ['model' => trans('comment.model')]),
//            Response::HTTP_OK
//        );
//    }
//
//    /**
//     * @OA\Get(
//     *     path="/comment/data",
//     *     operationId="getCommentData",
//     *     tags={"Comment"},
//     *     summary="Get Comment data",
//     *     description="Returns Comment data",
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
//        $this->authorize('create', Comment::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
