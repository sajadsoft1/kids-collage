<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CategoryResource",
 *     title="CategoryResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Category Title"),
 *     @OA\Property(property="description", type="string", default="Category Description"),
 *     @OA\Property(property="slug", type="string", default="category-title"),
 *     @OA\Property(property="published", type="string", default="active"),
 *     @OA\Property(property="type", type="string", default="blog"),
 *     @OA\Property(property="parent", ref="#/components/schemas/SimpleCategoryResource"),
 *     @OA\Property(property="children_count", type="integer", default="5"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'slug'           => $this->slug,
            'published'      => $this->published->value,
            'type'           => $this->type->value,
            'parent'         => $this->whenLoaded('parent', fn () => SimpleCategoryResource::make($this->parent)),
            'children_count' => $this->whenLoaded('children', fn () => $this->children->count(), 0),
            'image'          => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
            'updated_at'     => $this->updated_at,
            'created_at'     => $this->created_at,
        ];
    }
}
