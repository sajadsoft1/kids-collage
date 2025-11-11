<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlogResource;
use App\Http\Resources\BulletinResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\CourseTemplateResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\LicenseResource;
use App\Http\Resources\OpinionResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\UserResource;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Bulletin;
use App\Models\Client;
use App\Models\Course;
use App\Models\CourseTemplate;
use App\Models\Faq;
use App\Models\Home;
use App\Models\License;
use App\Models\Opinion;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
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
        $extraData = [
            'teachers_count' => User::teachers()->count(),
            'courses_count' => Course::all()->count(),
            'students_count' => User::studentCont(),
            'experience' => 10,
        ];
        $data = [
            'sliders' => SliderResource::collection(Slider::latestSliders()),
            'banners' => BannerResource::collection(Banner::latestBanner()),
            'teachers' => UserResource::collection(User::teachers()),
            'bulletins' => BulletinResource::collection(Bulletin::latestBulletin()),
            'blogs' => BlogResource::collection(Blog::latestBlogs()),
            'opinions' => OpinionResource::collection(Opinion::homeOpinions()),
            'clients' => ClientResource::collection(Client::homeClients()),
            'events' => [],
            'faqs' => FaqResource::collection(Faq::homeFaq()),
            'licenses' => LicenseResource::collection(License::homeLicenses()),
            'courseTemplate' => CourseTemplateResource::collection(CourseTemplate::latestCourseTemplates()),
            'extra_data' => $extraData,
        ];

        return Response::data($data);
    }
}
