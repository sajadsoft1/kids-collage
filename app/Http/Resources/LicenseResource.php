<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LicenseResource",
 *     title="LicenseResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="description", type="string", default="Description"),
 *     @OA\Property(property="slug", type="string", default="blog-title"),
 *     @OA\Property(property="view_count", type="integer", default=100),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 * )
 */
class LicenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'view_count' => $this->view_count,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_854_480),
        ];
    }
}
