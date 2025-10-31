<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\CourseTemplateDetailResource;
use App\Http\Resources\CourseTemplateResource;
use App\Models\CourseTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class CourseTemplateController extends Controller
{
    public function __construct() {}

    private function query(array $payload = []): QueryBuilder
    {
        return QueryBuilder::for(CourseTemplate::query())
            ->with(['media'])
            ->when($limit = Arr::get($payload, 'limit'), fn ($q) => $q->limit($limit))
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
            ])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(['translations' => ['title', 'description']])),
            ]);
    }

    /**
     * @OA\Get(
     *     path="/course-template",
     *     operationId="getCourseTemplates",
     *     tags={"CourseTemplate"},
     *     summary="get courseTemplate list",
     *     description="Returns list of courseTemplate",
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/page_limit"),
     *     @OA\Parameter(ref="#/components/parameters/search"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CourseTemplateResource")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", default="http://localhost/api/course-template?page=1"),
     *                 @OA\Property(property="last", type="string", default="http://localhost/api/course-template?page=4"),
     *                 @OA\Property(property="prev", type="string", default="null", nullable=true),
     *                 @OA\Property(property="next", type="string", default="http://localhost/api/course-template?page=2", nullable=true),
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
            ])->paginate($request->input('page_limit', 15))->toResourceCollection(CourseTemplateResource::class),
            [
                'sort' => [
                    ['label' => '', 'value' => 'id', 'selected' => true, 'default' => true],
                ],
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/course-template/{courseTemplate}",
     *     operationId="getCourseTemplateByID",
     *     tags={"CourseTemplate"},
     *     summary="Get courseTemplate information",
     *     description="Returns courseTemplate data",
     *     @OA\Parameter(name="courseTemplate", required=true, in="path", @OA\Schema(type="string")),
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="message", type="string", default="No message"),
     *         )
     *     )
     * )
     */
    public function show(CourseTemplate $courseTemplate): JsonResponse
    {
        $courseTemplate->recordView();

        return Response::data(
            [
                'courseTemplate' => CourseTemplateDetailResource::make($courseTemplate->load('courses', 'comments', 'seoOption')),
            ]
        );
    }
}
