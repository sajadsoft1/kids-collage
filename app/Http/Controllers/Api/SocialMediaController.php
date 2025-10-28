<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\SocialMediaResource;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class SocialMediaController extends Controller
{
    public function __construct() {}

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(SocialMedia::query())
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
    //     *     path="/social-media",
    //     *     operationId="getSocialMedias",
    //     *     tags={"SocialMedia"},
    //     *     summary="get socialMedia list",
    //     *     description="Returns list of socialMedia",
    //     *     @OA\Parameter(ref="#/components/parameters/page"),
    //     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
    //     *     @OA\Parameter(ref="#/components/parameters/search"),
    //     *     @OA\Parameter(ref="#/components/parameters/sort"),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SocialMediaResource")),
    //     *             @OA\Property(property="links", type="object",
    //     *                 @OA\Property(property="first", type="string", default="http://localhost/api/social-media?page=1"),
    //     *                 @OA\Property(property="last", type="string", default="http://localhost/api/social-media?page=4"),
    //     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
    //     *                 @OA\Property(property="next", type="string", default="http://localhost/api/social-media?page=2", nullable=true),
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
    //            ])->paginate($request->input('page_limit', 15))->toResourceCollection(SocialMediaResource::class),
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
    //     *     path="/social-media/{socialMedia}",
    //     *     operationId="getSocialMediaByID",
    //     *     tags={"SocialMedia"},
    //     *     summary="Get socialMedia information",
    //     *     description="Returns socialMedia data",
    //     *     @OA\Parameter(name="socialMedia", required=true, in="path", @OA\Schema(type="string")),
    //     *     @OA\Response(response=200,
    //     *         description="Successful operation",
    //     *         @OA\JsonContent(type="object",
    //     *             @OA\Property(property="message", type="string", default="No message"),
    //     *         )
    //     *     )
    //     * )
    //     */
    //    public function show(SocialMedia $socialMedia): JsonResponse
    //    {
    //        return Response::data(
    //            [
    //                'socialMedia' => SocialMediaDetailResource::make($socialMedia),
    //            ]
    //        );
    //    }

    /**
     * @OA\Get(
     *     path="/social-media/actives",
     *     operationId="getActiveSocialMedias",
     *     tags={"SocialMedia"},
     *     summary="get active socialMedia list",
     *     description="Returns list of socialMedia",
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     * @throws Throwable
     */
    public function actives(Request $request): JsonResponse
    {
        return Response::data(
            [
                'socialMedia' => SocialMediaResource::collection(SocialMedia::actives()),
            ]
        );
    }
}
