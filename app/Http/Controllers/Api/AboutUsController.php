<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\AboutUsDetailResource;
use App\Http\Resources\AboutUsResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\AboutUs;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class AboutUsController extends Controller
{
    public function __construct()
    {
    }

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(AboutUs::query())
            ->with([])
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
//     *     path="/about-us",
//     *     operationId="getAboutUss",
//     *     tags={"AboutUs"},
//     *     summary="get aboutUs list",
//     *     description="Returns list of aboutUs",
//     *     @OA\Parameter(ref="#/components/parameters/page"),
//     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
//     *     @OA\Parameter(ref="#/components/parameters/search"),
//     *     @OA\Parameter(ref="#/components/parameters/sort"),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AboutUsResource")),
//     *             @OA\Property(property="links", type="object",
//     *                 @OA\Property(property="first", type="string", default="http://localhost/api/about-us?page=1"),
//     *                 @OA\Property(property="last", type="string", default="http://localhost/api/about-us?page=4"),
//     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
//     *                 @OA\Property(property="next", type="string", default="http://localhost/api/about-us?page=2", nullable=true),
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
//            ])->paginate($request->input('page_limit', 15))->toResourceCollection(AboutUsResource::class),
//            [
//                'sort' => [
//                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
//                ],
//            ]
//        );
//    }
//
//    /**
//     * @OA\Get(
//     *     path="/about-us/{aboutUs}",
//     *     operationId="getAboutUsByID",
//     *     tags={"AboutUs"},
//     *     summary="Get aboutUs information",
//     *     description="Returns aboutUs data",
//     *     @OA\Parameter(name="aboutUs", required=true, in="path", @OA\Schema(type="string")),
//     *     @OA\Response(response=200,
//     *         description="Successful operation",
//     *         @OA\JsonContent(type="object",
//     *             @OA\Property(property="message", type="string", default="No message"),
//     *         )
//     *     )
//     * )
//     */
//    public function show(AboutUs $aboutUs): JsonResponse
//    {
//        return Response::data(
//            [
//                'aboutUs' => AboutUsDetailResource::make($aboutUs),
//            ]
//        );
//    }
    /**
     * @OA\Get(
     *     path="/about-us",
     *     operationId="getAboutUs",
     *     tags={"AboutUs"},
     *     summary="Get about us information",
     *     description="Returns about us data",
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/AboutUsDetailResource"),
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="extra", type="object"),
     *         )
     *     )
     * )
     */
    public function getAbout()
    {
        $teachers=User::teachers();
                return Response::data(
            [
                'aboutUs' => AboutUsDetailResource::make(Page::about()),
                'teachers'=>UserResource::collection($teachers)
            ]
        );
}
}
