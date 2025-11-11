<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleCategoryResource",
 *     title="SimpleCategoryResource",
 *
 *     @OA\Property(property="id", type="integer", description="Category id", example="1"),
 *     @OA\Property(property="title", type="string", example="Technology", nullable=true),
 *     @OA\Property(property="slug", type="string", example="technology"),
 *     @OA\Property(property="image", type="string", example="https://example.com/image.jpg"),
 * )
 */
class SimpleCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_512_SQUARE),
        ];
    }
}
