<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Notebook\DeleteNotebookAction;
use App\Actions\Notebook\StoreNotebookAction;
use App\Actions\Notebook\UpdateNotebookAction;
use App\Filters\FuzzyFilter;
use App\Http\Requests\StoreNotebookRequest;
use App\Http\Requests\UpdateNotebookRequest;
use App\Http\Resources\NotebookDetailResource;
use App\Http\Resources\NotebookResource;
use App\Models\Notebook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class NotebookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Notebook::query())
            ->with('user')
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->where('user_id', auth()->user()->id)
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter),
            ]);
    }

    /**
     * @OA\Get(
     *     path="/notebook",
     *     operationId="getNotebooks",
     *     tags={"Notebook"},
     *     summary="get notebooks list",
     *     description="Returns list of notebooks",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NotebookResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/notebook?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/notebook?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/notebook?page=2", nullable=true),
     *             ),
     *             @OA\Property(property="meta", ref="#/components/schemas/Meta"),
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="extra", type="object",
     *                 @OA\Property(property="default_sort", type="string", default="-id"),
     *                 @OA\Property(property="sorts", type="array", @OA\Items(type="object"),
     *                     @OA\Property(property="label", type="string", default="ID"),
     *                     @OA\Property(property="value", type="string", default="id"),
     *                     @OA\Property(property="selected", type="boolean", default=true),
     *                     @OA\Property(property="default", type="boolean", default=true),
     *                 ),
     *             ),
     *         )
     *     )
     * )
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        return Response::dataWithAdditional(
            $this->query([
                'limit' => $request->input('limit'),
            ])->paginate($request->input('page_limit', 15))->toResourceCollection(NotebookResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/notebook/{notebook}",
     *     operationId="getNotebookID",
     *     tags={"Notebook"},
     *     summary="Get notebook information",
     *     description="Returns notebook data",
     *     @OA\Parameter(name="notebook", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *
     *         )
     *     )
     * )
     */
    public function show(Notebook $notebook): JsonResponse
    {
        abort_if($notebook->user_id !== auth()->user()->id, 403);

        return Response::data(
            [
                'notebook' => NotebookDetailResource::make($notebook),
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/notebook",
     *     operationId="storeNotebook",
     *     tags={"Notebook"},
     *     summary="Store notebook",
     *     description="Store otebook",
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreNotebookRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NotebookResource")),
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function store(StoreNotebookRequest $request): JsonResponse
    {
        $notebook = StoreNotebookAction::run($request->validated());

        return Response::data(
            [
                'notebook' => NotebookDetailResource::make($notebook),
            ],
            trans('general.model_has_stored_successfully', ['model' => trans('notebook.model')])
        );
    }

    /**
     * @OA\Put(
     *     path="/notebook/{notebook}",
     *     operationId="updateNotebook",
     *     tags={"Notebook"},
     *     summary="Update notebook",
     *     description="Update notebook",
     *     @OA\Parameter(name="notebook", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/UpdateNotebookRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NotebookResource")),
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function update(UpdateNotebookRequest $request, Notebook $notebook): JsonResponse
    {
        abort_if($notebook->user_id !== auth()->user()->id, 403);
        $notebook = UpdateNotebookAction::run($notebook, $request->validated());

        return Response::data(
            [
                'notebook' => NotebookDetailResource::make($notebook),
            ],
            trans('general.model_has_updated_successfully', ['model' => trans('notebook.model')])
        );
    }

    /**
     * @OA\Delete(
     *     path="/notebook/{notebook}",
     *     operationId="deleteNotebook",
     *     tags={"Notebook"},
     *     summary="delete notebook",
     *     description="delete notebook",
     *     @OA\Parameter(name="notebook", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function destroy(Notebook $notebook): JsonResponse
    {
        abort_if($notebook->user_id !== auth()->user()->id, 403);
        $result = DeleteNotebookAction::run($notebook);

        return Response::data($result, trans('general.model_has_deleted_successfully', ['model' => trans('notebook.model')]));
    }
}
