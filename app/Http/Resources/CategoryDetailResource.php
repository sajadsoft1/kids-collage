<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CategoryDetailResource",
 *     title="CategoryDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Category Title"),
 *     @OA\Property(property="description", type="string", default="Category Description"),
 *     @OA\Property(property="body", type="string", default="Full category content..."),
 *     @OA\Property(property="slug", type="string", default="category-title"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="type", ref="#/components/schemas/CategoryTypeEnum"),
 *     @OA\Property(property="ordering", type="integer", default="1"),
 *     @OA\Property(property="languages", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="parent", ref="#/components/schemas/SimpleCategoryResource"),
 *     @OA\Property(property="children", type="array", @OA\Items(ref="#/components/schemas/CategoryResource")),
 *     @OA\Property(property="seo_option", type="object"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="view_count", type="integer", default="250"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class CategoryDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource = CategoryResource::make($this)->toArray($request);

        return array_merge($resource, [
            'body' => $this->body,
            'ordering' => $this->ordering,
            'languages' => $this->languages,
            'children' => $this->whenLoaded('children', fn () => CategoryResource::collection($this->children)),
            'seo_option' => $this->seoOption,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
            'view_count' => $this->view_count,
        ]);
    }
}
