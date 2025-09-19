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
 *     schema="NewsResource",
 *     title="NewsResource",
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
 * )
 */
class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'body'          => $this->body,
            'slug'          => $this->slug,
            'published'     => $this->published->toArray(),
            'published_at'  => $this->published_at,
            'image'         => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE),
            'view_count'    => $this->view_count,
            'comment_count' => $this->comment_count,
            'wish_count'    => $this->wish_count,
            'link'          => $this->link,
            'source'        => $this->source,
            'updated_at'    => $this->updated_at,
            'created_at'    => $this->created_at,
        ];
    }
}
