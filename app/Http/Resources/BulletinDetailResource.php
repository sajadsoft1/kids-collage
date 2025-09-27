<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="BulletinDetailResource",
 *     title="BulletinDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="bulletin Title"),
 *     @OA\Property(property="description", type="string", default="bulletin Description"),
 *     @OA\Property(property="slug", type="string", default="bulletin-title"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *          @OA\Property(property="comments", type="array", @OA\Items(ref="#/components/schemas/CommentResource")),
 *          @OA\Property(property="user", ref="#/components/schemas/UserResource"),
 *      @OA\Property(property="category", ref="#/components/schemas/CategoryResource"),
 *      @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/TagResource")),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="view_count", type="integer", default="100"),
 *     @OA\Property(property="comment_count", type="integer", default="10"),
 *     @OA\Property(property="wish_count", type="integer", default="50"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *          @OA\Property(property="seo_option", type="object"),
 * )
 */
class BulletinDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'comments' => $this->whenLoaded('comments', fn() => CommentResource::collection($this->comments)),
            'user'          => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'category'      => $this->whenLoaded('category', fn () => CategoryResource::make($this->category)),
            'tags'          => $this->whenLoaded('tags', fn () => TagResource::collection($this->tags)),
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720),
            'seo_option' => $this->seoOption,

        ];
    }
}
