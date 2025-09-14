<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BlogResource",
 *     title="BlogResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Blog Title"),
 *     @OA\Property(property="description", type="string", default="Blog Description"),
 *     @OA\Property(property="slug", type="string", default="blog-title"),
 *     @OA\Property(property="published", type="string", default="active"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="user", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="category", ref="#/components/schemas/SimpleCategoryResource"),
 *     @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/SimpleTagResource")),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="view_count", type="integer", default="100"),
 *     @OA\Property(property="comment_count", type="integer", default="10"),
 *     @OA\Property(property="wish_count", type="integer", default="50"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'slug'          => $this->slug,
            'published'     => $this->published->toArray(),
            'published_at'  => $this->published_at,
            'user'          => $this->whenLoaded('user', fn () => SimpleUserResource::make($this->user)),
            'category'      => $this->whenLoaded('category', fn () => SimpleCategoryResource::make($this->category)),
            'tags'          => $this->whenLoaded('tags', fn () => SimpleTagResource::collection($this->tags)),
            'image'         => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_854_480),
            'view_count'    => $this->view_count,
            'comment_count' => $this->comment_count,
            'wish_count'    => $this->wish_count,
            'updated_at'    => $this->updated_at,
            'created_at'    => $this->created_at,
        ];
    }
}
