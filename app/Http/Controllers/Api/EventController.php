<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Comment\StoreCommentAction;
use App\Filters\DateFilter;
use App\Filters\FuzzyFilter;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\EventDetailResource;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Sorts\MostCommentSort;
use App\Sorts\MostWishSort;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Enums\SortDirection;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class EventController extends Controller
{
    public function __construct() {}

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(Event::query())
            ->with(['category', 'media'])
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->when($tag = Arr::get($payload, 'tag'), fn ($q) => $q->withAnyTags([$tag->name], $tag->type))
            ->when($userId = Arr::get($payload, 'user_id'), fn ($q) => $q->where('user_id', $userId))
            ->where('published', true)
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
                AllowedSort::custom('view', new MostCommentSort)->defaultDirection(SortDirection::DESCENDING),
                AllowedSort::custom('comment', new MostCommentSort)->defaultDirection(SortDirection::DESCENDING),
                AllowedSort::custom('wish', new MostWishSort)->defaultDirection(SortDirection::DESCENDING),
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
                AllowedFilter::custom('date', new DateFilter),
            ]);
    }

    /**
     * @OA\Get(
     *     path="/event",
     *     operationId="getEvents",
     *     tags={"Event"},
     *     summary="get event list",
     *     description="Returns list of event",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/EventResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/event?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/event?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/event?page=2", nullable=true),
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
            ])->paginate($request->input('page_limit', 15))->toResourceCollection(EventResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id'],
                    ['label' => trans('blog.sort.most_view'), 'value' => 'view'],
                    ['label' => trans('blog.sort.most_comment'), 'value' => 'comment'],
                    ['label' => trans('blog.sort.most_wish'), 'value' => 'wish'],
                ],
                'filter' => [
                    'date' => [
                        ['label' => trans('blog.filter.last_week'), 'value' => 'this_week'],
                        ['label' => trans('blog.filter.last_month'), 'value' => 'this_month'],
                        ['label' => trans('blog.filter.last_year'), 'value' => 'this_year'],
                    ],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/event/{event}",
     *     operationId="getEventByID",
     *     tags={"Event"},
     *     summary="Get event information",
     *     description="Returns event data",
     *     @OA\Parameter(name="event", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     */
    public function show(Event $event): JsonResponse
    {
        return Response::data(
            [
                'event' => EventDetailResource::make($event->load('siteComments', 'siteComments.user', 'siteComments.children')),
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/event/{event}/comment",
     *     operationId="storeCommentEvent",
     *     tags={"Event"},
     *     summary="Store event comment",
     *     description="Store comment",
     *     @OA\Parameter(name="event", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         ),
     *     )
     * )
     */
    public function storeUserComment(StoreCommentRequest $request, Event $event)
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['published'] = false;
        $data['morphable_type'] = Event::class;
        $data['morphable_id'] = $event->id;
        StoreCommentAction::run($data);

        return Response::data(
            [],
            trans('comment.store_successfully')
        );
    }
}
