<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PortfolioResource",
 *     title="PortfolioResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Website Redesign"),
 *     @OA\Property(property="description", type="string", default="Modern responsive design"),
 *     @OA\Property(property="published", type="string", default="active"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="execution_date", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="category", ref="#/components/schemas/SimpleCategoryResource"),
 *     @OA\Property(property="creator", ref="#/components/schemas/SimpleUserResource"),
 *     @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/SimpleTagResource")),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="view_count", type="integer", default="150"),
 *     @OA\Property(property="like_count", type="integer", default="25"),
 *     @OA\Property(property="wish_count", type="integer", default="10"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class PortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'published'      => $this->published->value,
            'published_at'   => $this->published_at,
            'execution_date' => $this->execution_date,
            'category'       => $this->whenLoaded('category', fn () => SimpleCategoryResource::make($this->category)),
            'creator'        => $this->whenLoaded('creator', fn () => SimpleUserResource::make($this->creator)),
            'tags'           => $this->whenLoaded('tags', fn () => SimpleTagResource::collection($this->tags)),
            'image'          => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
            'view_count'     => $this->view_count,
            'like_count'     => $this->like_count,
            'wish_count'     => $this->wish_count,
            'updated_at'     => $this->updated_at,
            'created_at'     => $this->created_at,
        ];
    }
}
