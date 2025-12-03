<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Taxonomy\DeleteTaxonomyAction;
use App\Actions\Taxonomy\StoreTaxonomyAction;
use App\Actions\Taxonomy\UpdateTaxonomyAction;
use App\Filters\FuzzyFilter;
use App\Http\Requests\StoreTaxonomyRequest;
use App\Http\Requests\UpdateTaxonomyRequest;
use App\Http\Resources\TaxonomyResource;
use App\Models\Taxonomy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class TaxonomyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Taxonomy::query())
            ->with([])
            ->where('user_id', auth()->user()->id)
            ->where('type', Arr::get($payload, 'type', 'flashcard'))
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
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
     *     path="/taxonomy",
     *     operationId="getTaxonomys",
     *     tags={"Taxonomy"},
     *     summary="get taxonomy list",
     *     description="Returns list of taxonomy",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TaxonomyResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/taxonomy?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/taxonomy?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/taxonomy?page=2", nullable=true),
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
                'type' => $request->input('type'),
            ])->paginate($request->input('page_limit', 15))->toResourceCollection(TaxonomyResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/taxonomy/{taxonomy}",
     *     operationId="getTaxonomyByID",
     *     tags={"Taxonomy"},
     *     summary="Get taxonomy information",
     *     description="Returns taxonomy data",
     *     @OA\Parameter(name="taxonomy", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     */
    public function show(Taxonomy $taxonomy): JsonResponse
    {
        return Response::data(
            [
                'taxonomy' => TaxonomyResource::make($taxonomy),
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/taxonomy",
     *     operationId="storeTaxonomy",
     *     tags={"Taxonomy"},
     *     summary="Store taxonomy",
     *     description="Store otebook",
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreTaxonomyRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TaxonomyResource")),
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function store(StoreTaxonomyRequest $request): JsonResponse
    {
        $taxonomy = StoreTaxonomyAction::run($request->validated());

        return Response::data(
            [
                'taxonomy' => TaxonomyResource::make($taxonomy),
            ],
            trans('general.model_has_stored_successfully', ['model' => trans('category.model')])
        );
    }

    /**
     * @OA\Put(
     *     path="/taxonomy/{taxonomy}",
     *     operationId="updateTaxonomy",
     *     tags={"Taxonomy"},
     *     summary="Update taxonomy",
     *     description="Update taxonomy",
     *     @OA\Parameter(name="taxonomy", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/UpdateTaxonomyRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TaxonomyResource")),
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function update(UpdateTaxonomyRequest $request, Taxonomy $taxonomy): JsonResponse
    {
        abort_if($taxonomy->user_id !== auth()->user()->id, 403);
        $taxonomy = UpdateTaxonomyAction::run($taxonomy, $request->validated());

        return Response::data(
            [
                'taxonomy' => TaxonomyResource::make($taxonomy),
            ],
            trans('general.model_has_updated_successfully', ['model' => trans('category.model')])
        );
    }

    /**
     * @OA\Delete(
     *     path="/taxonomy/{taxonomy}",
     *     operationId="deleteTaxonomy",
     *     tags={"Taxonomy"},
     *     summary="delete taxonomy",
     *     description="delete taxonomy",
     *     @OA\Parameter(name="taxonomy", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function destroy(Taxonomy $taxonomy): JsonResponse
    {
        abort_if($taxonomy->user_id !== auth()->user()->id, 403);
        $result = DeleteTaxonomyAction::run($taxonomy);

        return Response::data($result, trans('general.model_has_deleted_successfully', ['model' => trans('category.model')]));
    }
}
