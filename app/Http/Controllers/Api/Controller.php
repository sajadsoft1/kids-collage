<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Traits\HasViewTracking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Laravel OpenApi Demo Documentation",
 *     description="L5 Swagger OpenApi description",
 *
 *     @OA\Contact(
 *         email="admin@admin.com"
 *     ),
 *
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https:///www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local Host"
 * )
 *
 * @OA\Schema(
 *     schema="Meta",
 *     title="Pagination",
 *
 *     @OA\Property(property="current_page", type="integer", description="Current page number", default=1),
 *     @OA\Property(property="from", type="integer", description="First page number", default=1),
 *     @OA\Property(property="last_page", type="integer", description="Last page number", default=3),
 *     @OA\Property(property="links", type="array", description="Links of pages",
 *
 *         @OA\Items(
 *             type="object",
 *
 *             @OA\Property(property="url", type="string", description="Url of page", default="http://localhost:8000/api/shift?page=1"),
 *             @OA\Property(property="label", type="string", description="Label of page", default="1"),
 *             @OA\Property(property="active", type="boolean", description="this page is active or not", default=true)
 *         ),
 *         default={
 *             {
 *                 "url" : null,
 *                 "label" : "&laquo; Previous",
 *                 "active" : false
 *             },
 *             {
 *                 "url" : "http://localhost:8000/api/category?page=1",
 *                 "label" : "1",
 *                 "active" : true
 *             },
 *             {
 *                 "url" : "http://localhost:8000/api/category?page=2",
 *                 "label" : "2",
 *                 "active" : false
 *             },
 *             {
 *                 "url" : "http://127.0.0.1:8000/api/category?page=3",
 *                 "label" : "3",
 *                 "active" : false
 *             },
 *             {
 *                 "url" : "http://localhost:8000/api/category?page=2",
 *                 "label" : "Next &raquo;",
 *                 "active" : false
 *             },
 *         }
 *     ),
 *     @OA\Property(property="path", type="string", description="Base url path", default="http://localhost:8000/api/category"),
 *     @OA\Property(property="per_page", type="integer", description="Number of items per page", default=15),
 *     @OA\Property(property="to", type="integer", description="Last page number", default=10),
 *     @OA\Property(property="total", type="integer", description="number of records", default=50),
 * )
 *
 * @OA\Schema(
 *     schema="Pagination",
 *     title="Pagination",
 *
 *     @OA\Property(property="message", type="string", default="example message"),
 *     @OA\Property(property="data", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="meta", ref="#/components/schemas/Meta")
 * ),
 *
 * @OA\Parameter(
 *     parameter="advanced_search",
 *     name="filter[a_search][]",
 *     in="query",
 *     description="Search in events",
 *     required=false,
 *
 *     @OA\Schema(
 *         type="array",
 *
 *         @OA\Items(
 *             type="object",
 *
 *             @OA\Property(property="column", type="string", default="id"),
 *             @OA\Property(property="operator", type="string", default="=", enum={"like", "=", ">", "<", ">=", "<="}),
 *             @OA\Property(property="from", type="string", default="1"),
 *             @OA\Property(property="to", type="string", default="", nullable=true),
 *             @OA\Property(property="contain", type="integer", default="1", enum={0, 1}),
 *         )
 *     )
 * )
 *
 * @OA\Parameter(
 *     parameter="filter",
 *     name="filter",
 *     in="query",
 *     description="Filter shifts",
 *     required=false,
 *
 *     @OA\Schema(
 *         type="object",
 *
 *         @OA\Property(property="search", type="string", default=""),
 *     )
 * )
 *
 * @OA\Parameter(
 *     parameter="search",
 *     name="filter[search]",
 *     in="query",
 *     required=false,
 *
 *     @OA\Schema(type="string", default=""),
 * )
 *
 * @OA\Parameter(
 *     parameter="name",
 *     name="filter[name]",
 *     in="query",
 *     required=false,
 *
 *     @OA\Schema(type="string", default=""),
 * )
 *
 * @OA\Parameter(
 *     parameter="sort",
 *     name="sort",
 *     in="query",
 *     required=false,
 *
 *     @OA\Schema(type="array", @OA\Items(type="string"), default={"-id"}, default={"-id"}),
 *     description="Sort criteria for shifts (e.g., name, -created_at)"
 * )
 *
 * @OA\Parameter(parameter="page", name="page", in="query", required=false, @OA\Schema(type="integer", default=1), description="page number")
 * @OA\Parameter(parameter="page_limit", name="page_limit", in="query", required=false, @OA\Schema(type="integer", default=15), description="number of items per page")
 *
 * @OA\Response(
 *     response="400",
 *     description="Bad Request",
 *
 *     @OA\JsonContent(@OA\Property(property="message", type="string", default="Bad Request."))
 * )
 *
 * @OA\Response(
 *     response="401",
 *     description="Unauthorized",
 *
 *     @OA\JsonContent(@OA\Property(property="message", type="string", default="Unauthenticated."))
 * )
 *
 * @OA\Response(
 *     response="403",
 *     description="Forbidden",
 *
 *     @OA\JsonContent(@OA\Property(property="message", type="string", default="Forbidden."))
 * )
 *
 * @OA\Response(
 *     response="500",
 *     description="Internal Server Error",
 *
 *     @OA\JsonContent(@OA\Property(property="message", type="string", default="Internal Server Error."))
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
