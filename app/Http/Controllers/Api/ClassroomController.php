<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Classroom\ToggleClassroomAction;
use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateClassroomRequest;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Resources\ClassroomDetailResource;
use App\Http\Resources\ClassroomResource;
use App\Actions\Classroom\StoreClassroomAction;
use App\Actions\Classroom\DeleteClassroomAction;
use App\Actions\Classroom\UpdateClassroomAction;
use App\Repositories\Classroom\ClassroomRepositoryInterface;
use App\Services\AdvancedSearchFields\AdvancedSearchFieldsService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ClassroomController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Classroom::class);
    }

    /**
     * @OA\Get(
     *     path="/classroom",
     *     operationId="getClassrooms",
     *     tags={"Classroom"},
     *     summary="get classrooms list",
     *     description="Returns list of classrooms",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter (ref="#/components/parameters/search"),
     *     @OA\Parameter (ref="#/components/parameters/advanced_search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ClassroomResource")),
                         *             @OA\Property(property="links", type="object",
                         *                 @OA\Property(property="first", type="string", default="http://localhost/api/classroom?page=1"),
                         *                 @OA\Property(property="last", type="string", default="http://localhost/api/classroom?page=4"),
                         *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
                         *                 @OA\Property(property="next", type="string", default="http://localhost/api/classroom?page=2", nullable=true),
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
    public function index(ClassroomRepositoryInterface $repository): JsonResponse
    {
        return Response::dataWithAdditional(
            ClassroomResource::collection($repository->paginate(payload: [
                'with' => [],
            ])),
            additional: [
                'advance_search_field' => AdvancedSearchFieldsService::generate(Classroom::class),
                'extra'                => $repository->extra(),
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/classroom/{classroom}",
     *     operationId="getClassroomByID",
     *     tags={"Classroom"},
     *     summary="Get classroom information",
     *     description="Returns classroom data",
     *     @OA\Parameter(name="classroom", required=true,in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="No message"),
     *              @OA\Property(property="data",ref="#/components/schemas/ClassroomDetailResource")
     *          )
     *      )
     * )
     */
    public function show(Classroom $classroom): JsonResponse
    {
        return Response::data(
            ClassroomDetailResource::make($classroom->load('category', 'user')),
        );
    }

    /**
     * @OA\Post(
     *     path="/classroom",
     *     operationId="storeClassroom",
     *     tags={"Classroom"},
     *     summary="Store new classroom",
     *     description="Returns new classroom data",
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreClassroomRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/StoreClassroomRequest"))
     *     ),
     *     @OA\Response(response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="classroom has been stored successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/ClassroomResource")
     *          )
     *      )
     * )
     */
    public function store(StoreClassroomRequest $request): JsonResponse
    {
        $model = StoreClassroomAction::run($request->validated());
        return Response::data(
            ClassroomResource::make($model),
            trans('general.model_has_stored_successfully',['model'=>trans('classroom.model')]),
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Put(
     *     path="/classroom/{classroom}",
     *     operationId="updateClassroom",
     *     tags={"Classroom"},
     *     summary="Update existing classroom",
     *     description="Returns updated classroom data",
     *     @OA\Parameter(name="classroom",required=true,in="path",@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateClassroomRequest"),
     *          @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/UpdateClassroomRequest"))
     *     ),
     *     @OA\Response(response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="message",type="string",default="classroom has been updated successfully"),
     *              @OA\Property(property="data",ref="#/components/schemas/ClassroomResource")
     *          )
     *      )
     * )
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom): JsonResponse
    {
        $data = UpdateClassroomAction::run($classroom, $request->validated());
        return Response::data(
            ClassroomResource::make($data),
            trans('general.model_has_updated_successfully',['model'=>trans('classroom.model')]),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     *      path="/classroom/{classroom}",
     *      operationId="deleteClassroom",
     *      tags={"Classroom"},
     *      summary="Delete existing classroom",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(name="classroom",required=true,in="path",@OA\Schema(type="integer")),
     *      @OA\Response(response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="object",
     *              @OA\Property(property="data", type="boolean", default=true),
     *              @OA\Property(property="message",type="string",default="classroom has been deleted successfully")
     *          ),
     *      )
     * )
     */
    public function destroy(Classroom $classroom): JsonResponse
    {
        DeleteClassroomAction::run($classroom);
        return Response::data(
            true,
            trans('general.model_has_deleted_successfully',['model'=>trans('classroom.model')]),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *     path="/classroom/toggle/{classroom}",
     *     operationId="toggleClassroom",
     *     tags={"Classroom"},
     *     summary="Toggle Classroom",
     *     @OA\Parameter(name="classroom", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="classroom has been toggled successfully"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/ClassroomResource")
     *         )
     *     )
     * )
     */
    public function toggle(Classroom $classroom): JsonResponse
    {
        $this->authorize('update', $classroom);
        $classroom = ToggleClassroomAction::run($classroom);

        return Response::data(
            ClassroomResource::make($classroom),
            trans('general.model_has_toggled_successfully', ['model' => trans('classroom.model')]),
            Response::HTTP_OK
        );
    }
//
//    /**
//     * @OA\Get(
//     *     path="/classroom/data",
//     *     operationId="getClassroomData",
//     *     tags={"Classroom"},
//     *     summary="Get Classroom data",
//     *     description="Returns Classroom data",
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
//        $this->authorize('create', Classroom::class);
//        return Response::data(
//            [
//                'user'  => $request->user()
//            ]
//        );
//    }
}
