<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\ContactUs\StoreContactUsAction;
use App\Filters\FuzzyFilter;
use App\Http\Requests\StoreContactUsRequest;
use App\Models\ContactUs;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ContactUsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum')->only(['index', 'show']);
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(ContactUs::query())
            ->with()
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->where('published', true)
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
            ]);
    }

    //    /**
    //     * @OA\Get(
    //     *     path="/contact-us",
    //     *     operationId="getContactUss",
    //     *     tags={"ContactUs"},
    //     *     summary="get contactUs list",
    //     *     description="Returns list of contactUs",
    //     *     @OA\Parameter(ref="#/components/parameters/page"),
    //     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
    //     *     @OA\Parameter(ref="#/components/parameters/search"),
    //     *     @OA\Parameter(ref="#/components/parameters/sort"),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ContactUsResource")),
    //     *             @OA\Property(property="links", type="object",
    //     *                 @OA\Property(property="first", type="string", default="http://localhost/api/contact-us?page=1"),
    //     *                 @OA\Property(property="last", type="string", default="http://localhost/api/contact-us?page=4"),
    //     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
    //     *                 @OA\Property(property="next", type="string", default="http://localhost/api/contact-us?page=2", nullable=true),
    //     *             ),
    //     *             @OA\Property(property="meta", ref="#/components/schemas/Meta"),
    //     *             @OA\Property(property="message", type="string", default="No message"),
    //     *             @OA\Property(property="extra", type="object",
    //     *                 @OA\Property(property="default_sort", type="string", default="-id"),
    //     *                 @OA\Property(property="sorts", type="array", @OA\Items(type="object"),
    //     *                     @OA\Property(property="label", type="string", default="ID"),
    //     *                     @OA\Property(property="value", type="string", default="id"),
    //     *                     @OA\Property(property="selected", type="boolean", default=true),
    //     *                     @OA\Property(property="default", type="boolean", default=true),
    //     *                 ),
    //     *             ),
    //     *         )
    //     *     )
    //     * )
    //     * @throws Throwable
    //     */
    //    public function index(Request $request): JsonResponse
    //    {
    //        return Response::dataWithAdditional(
    //            $this->query([
    //                'limit' => $request->input('limit'),
    //            ])->paginate($request->input('page_limit', 15))->toResourceCollection(ContactUsResource::class),
    //            [
    //                'sort' => [
    //                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
    //                ],
    //            ]
    //        );
    //    }

    //    /**
    //     * @OA\Get(
    //     *     path="/contact-us/{contactUs}",
    //     *     operationId="getContactUsByID",
    //     *     tags={"ContactUs"},
    //     *     summary="Get contactUs information",
    //     *     description="Returns contactUs data",
    //     *     @OA\Parameter(name="contactUs", required=true, in="path", @OA\Schema(type="string")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="No message"),
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function show(ContactUs $contactUs): JsonResponse
    //    {
    //        return Response::data(
    //            [
    //                'contactUs' => ContactUsDetailResource::make($contactUs),
    //            ]
    //        );
    //    }

    /**
     * @OA\Post(
     *     path="/contact-us",
     *     operationId="storeContactUs",
     *     tags={"ContactUs"},
     *     summary="Store contactUs",
     *     description="Store contactUs",
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreContactUsRequest")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="object", example={"message" : "پیام شما با موفقیت ارسال شد. با تشکر از تماس شما"}),
     *         ),
     *     )
     * )
     */
    public function store(StoreContactUsRequest $request)
    {
        StoreContactUsAction::run($request->validated());

        return Response::data(message: trans('contactUs.notifications.store'));
    }
}
