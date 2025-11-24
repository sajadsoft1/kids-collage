<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="EventDetailResource",
 *     title="EventDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Event Title"),
 *     @OA\Property(property="description", type="string", default="Event Description"),
 *     @OA\Property(property="body", type="string", default="Full event content..."),
 *     @OA\Property(property="slug", type="string", default="event-title"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="category", ref="#/components/schemas/CategoryResource"),
 *     @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/TagResource")),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="view_count", type="integer", default="100"),
 *     @OA\Property(property="comment_count", type="integer", default="10"),
 *     @OA\Property(property="wish_count", type="integer", default="50"),
 *     @OA\Property(property="capacity", type="integer", default="50"),
 *     @OA\Property(property="price", type="integer", default="50"),
 *     @OA\Property(property="location", type="string", default="masshad city"),
 *     @OA\Property(property="is_online", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="start_date", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="end_date", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="comments", type="array", @OA\Items(ref="#/components/schemas/SimpleCommentResource")),
 *     @OA\Property(property="seo_option", type="object"),
 * )
 */
class EventDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = EventResource::make($this)->toArray($request);

        return array_merge($resource, [
            'body' => $this->body,
            'comments' => $this->whenLoaded('comments', fn () => CommentResource::collection($this->comments)),
            'seo_option' => $this->seoOption,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720),
            //            'liked' => $this->resource?->isWished(),
        ]);
    }
}
