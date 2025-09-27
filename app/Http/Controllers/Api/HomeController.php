<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\BulletinResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OpinionResource;
use App\Http\Resources\SliderResource;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Bulletin;
use App\Models\Client;
use App\Models\Home;
use App\Models\Opinion;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class HomeController extends Controller
{
    public function __construct() {}

    //    private function query(array $payload = []): QueryBuilder
    //    {
    //        return QueryBuilder::for(Home::query())
    //            ->with([])
    //            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
    //            ->where('published', true)
    //            ->defaultSort('-id')
    //            ->allowedSorts([
    //                'id',
    //            ])
    //            ->allowedFilters([
    //                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
    //            ]);
    //    }

    /**
     * @OA\Get(
     *     path="/home",
     *     operationId="getHomes",
     *     tags={"Home"},
     *     summary="get home list",
     *     description="Returns list of home",
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *     ),
     * )
     *     )
     * )
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        $data = Cache::remember('home_page_data', 5000, function () {
            return [
                'sliders'   => SliderResource::collection(
                    Slider::where('published', true)->limit(10)->get()
                ),
                'banners'   => BannerResource::collection(
                    Banner::where('published', true)->limit(10)->get()
                ),
                'teachers'  => [],
                'bulletins' => BulletinResource::collection(
                    Bulletin::where('published', true)->orderByDesc('id')->limit(12)->get()
                ),
                'blogs'     => BlogResource::collection(
                    Blog::where('published', true)->orderByDesc('id')->limit(12)->get()
                ),
                'opinions'   => OpinionResource::collection(
                    Opinion::where('published', true)->get()
                ),
                'clients'    => ClientResource::collection(
                    Client::where('published', true)->get()
                ),
                'events'     => [],
            ];
        });

        return Response::data($data);
    }
}
