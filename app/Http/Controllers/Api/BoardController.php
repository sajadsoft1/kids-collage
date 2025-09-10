<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Board;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateBoardRequest;
use App\Http\Requests\StoreBoardRequest;
use App\Http\Resources\BoardDetailResource;
use App\Http\Resources\BoardResource;
use App\Actions\Board\StoreBoardAction;
use App\Actions\Board\DeleteBoardAction;
use App\Actions\Board\UpdateBoardAction;
use App\Repositories\Board\BoardRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class BoardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Board::class);
    }

    /**
     * @OA\Get(
     *     path="/board",
     *     operationId="getBoards",
     *     tags={"Board"},
     *     summary="get boards list",
     *     description="Returns list of boards",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/BoardResource")),
                         *             @OA\Property(property="links", type="object",
                         *                 @OA\Property(property="first", type="string", default="http://localhost/api/board?page=1"),
                         *                 @OA\Property(property="last", type="string", default="http://localhost/api/board?page=4"),
                         *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
                         *                 @OA\Property(property="next", type="string", default="http://localhost/api/board?page=2", nullable=true),
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
    public function index(BoardRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            BoardResource::collection($repository->paginate()),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Board::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/board/{board}",
     *     operationId="getBoardByID",
     *     tags={"Board"},
     *     summary="Get board information",
     *     description="Returns board data",
     *     @OA\Parameter(name="board", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/BoardDetailResource")
     *          )
     *      )
     * )
     */
    public function show(Board $board): JsonResponse
    {
        return Response::data(
            BoardDetailResource::make($board),
        );
    }

    /**
     * @OA\Post(
     *     path="/board",
     *     operationId="storeBoard",
     *     tags={"Board"},
     *     summary="Store new board",
     *     description="Returns new board data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreBoardRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreBoardRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="board has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/BoardResource")
     *          )
     *      )
     * )
     */
    public function store(StoreBoardRequest $request): JsonResponse
    {
        $model = StoreBoardAction::run($request->validated());
        return Response::data(
            BoardResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('board.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/board/{board}",
     *     operationId="updateBoard",
     *     tags={"Board"},
     *     summary="Update existing board",
     *     description="Returns updated board data",
     *     @OA\Parameter(name="board",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateBoardRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateBoardRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="board has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/BoardResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateBoardRequest $request, Board $board): JsonResponse
    {
        $data = UpdateBoardAction::run($board, $request->validated());
        return Response::data(
            BoardResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('board.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/board/{board}",
     *      operationId="deleteBoard",
     *      tags={"Board"},
     *      summary="Delete existing board",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="board",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="board has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(Board $board): JsonResponse
    {
        DeleteBoardAction::run($board);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('board.model')]),
            Response::HTTP_OK
        );
    }

//    /**
//     * @OA\Post(
//     *     path="/board/toggle/{board}",
//     *     operationId="toggleBoard",
//     *     tags={"Board"},
//     *     summary="Toggle Board",
//     *     @OA\Parameter(name="board", required=true, in="path", @OA\Schema(type="integer")),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="board has been toggled successfully"),
//     *             @OA\Property(property="data", type="object", ref="#/components/schemas/BoardResource")
//     *         )
//     *     )
//     * )
//     */
//    public function toggle(Board $board): JsonResponse
//    {
//        $this->authorize('update', $board);
//        $board = ToggleBoardAction::run($board);
//
//        return Response::data(
//            BoardResource::make($board),
//            trans('general.model_has_toggled_successfully', ['model' => trans('board.model')]),
//            Response::HTTP_OK
//        );
//    }
//
//    /**
//     * @OA\Get(
//     *     path="/board/data",
//     *     operationId="getBoardData",
//     *     tags={"Board"},
//     *     summary="Get Board data",
//     *     description="Returns Board data",
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
//        $this->authorize('create', Board::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
