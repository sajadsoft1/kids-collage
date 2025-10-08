<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\FuzzyFilter;
use App\Http\Resources\RuleControllerDetailResource;
use App\Http\Resources\RuleControllerResource;
use App\Http\Resources\RuleDetailResource;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class RuleController extends Controller
{
    public function __construct()
    {
    }
    /**
     * @OA\Get(
     *     path="/rules",
     *     operationId="getRules",
     *     tags={"Rules"},
     *     summary="Get Rules information",
     *     description="Returns Rules data",
     *     @OA\Response(response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/RuleDetailResource"),
     *             @OA\Property(property="message", type="string", default="No message"),
     *             @OA\Property(property="extra", type="object"),
     *         )
     *     )
     * )
     */
    public function getRules()
    {

        return Response::data(
            [
                'rules' => RuleDetailResource::make(Page::rules()),
            ]
        );
    }
}
