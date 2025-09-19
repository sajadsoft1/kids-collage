<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="NewsDetailResource",
 *     title="NewsDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="News Title"),
 *     @OA\Property(property="description", type="string", default="News Description"),
 *     @OA\Property(property="body", type="string", default="News Body"),
 *     @OA\Property(property="slug", type="string", default="news-title"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="http://localhost/storage/news/image/100x100/example.jpg"),
 *     @OA\Property(property="view_count", type="integer", default="10"),
 *     @OA\Property(property="comment_count", type="integer", default="5"),
 *     @OA\Property(property="wish_count", type="integer", default="3"),
 *     @OA\Property(property="link", type="string", default="http://example.com/news"),
 *     @OA\Property(property="source", type="string", default="News Source"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="comments", type="array", @OA\Items(ref="#/components/schemas/SimpleCommentResource")),
 *     @OA\Property(property="seo_option", type="object"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserResource"),
 *     @OA\Property(property="category", ref="#/components/schemas/CategoryResource"),
 *
 * )
 */
class NewsDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource               = NewsResource::make($this)->toArray($request);
        $resource['id']         = $this->id;
        $resource['comments']   = $this->whenLoaded('comments', fn () => SimpleCommentResource::collection($this->comments));
        $resource['seo_option'] = $this->whenLoaded('seoOption', fn () => $this->seoOption);
        $resource['user']       = $this->whenLoaded('user', fn () => UserResource::make($this->user));
        $resource['category']   = $this->whenLoaded('category', fn () => UserResource::make($this->category));
        $resource['image']   = $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720);

        return $resource;
    }
}
