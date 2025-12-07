<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Note\DeleteNoteAction;
use App\Actions\Note\StoreNoteAction;
use App\Actions\Note\UpdateNoteAction;
use App\Filters\FuzzyFilter;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Note::query())
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
     *     path="/note",
     *     operationId="getNotes",
     *     tags={"Note"},
     *     summary="get note list",
     *     description="Returns list of note",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NoteResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/note?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/note?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/note?page=2", nullable=true),
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
            ])->paginate($request->input('page_limit', 15))->toResourceCollection(NoteResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/note/{note}",
     *     operationId="getNoteID",
     *     tags={"Note"},
     *     summary="Get note information",
     *     description="Returns note data",
     *     @OA\Parameter(name="note", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *
     *         )
     *     )
     * )
     */
    public function show(Note $note): JsonResponse
    {
        abort_if($note->user_id !== auth()->user()->id, 403);

        return Response::data(
            [
                'note' => NoteResource::make($note),
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/note",
     *     operationId="storeNote",
     *     tags={"Note"},
     *     summary="Store note",
     *     description="Store notebook",
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreNoteRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NoteResource")),
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function store(StoreNoteRequest $request): JsonResponse
    {
        $note = StoreNoteAction::run($request->validated());

        return Response::data(
            [
                'note' => NoteResource::make($note),
            ],
            trans('general.model_has_stored_successfully', ['model' => trans('note.model')])
        );
    }

    /**
     * @OA\Put(
     *     path="/note/{note}",
     *     operationId="updateNote",
     *     tags={"Note"},
     *     summary="Update note",
     *     description="Update note",
     *     @OA\Parameter(name="note", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/UpdateNoteRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NoteResource")),
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        abort_if($note->user_id !== auth()->user()->id, 403);
        $note = UpdateNoteAction::run($note, $request->validated());

        return Response::data(
            [
                'note' => NoteResource::make($note),
            ],
            trans('general.model_has_updated_successfully', ['model' => trans('note.model')])
        );
    }

    /**
     * @OA\Delete(
     *     path="/note/{note}",
     *     operationId="deleteNote",
     *     tags={"Note"},
     *     summary="delete note",
     *     description="delete note",
     *     @OA\Parameter(name="notebook", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function destroy(Note $note): JsonResponse
    {
        abort_if($note->user_id !== auth()->user()->id, 403);
        $result = DeleteNoteAction::run($note);

        return Response::data($result, trans('general.model_has_deleted_successfully', ['model' => trans('note.model')]));
    }
}
