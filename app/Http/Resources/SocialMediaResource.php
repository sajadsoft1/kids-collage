<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SocialMediaResource",
 *     title="SocialMediaResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="SocialMedia Title"),
 *     @OA\Property(property="link", type="string", default="https://www.example.com"),
 *     @OA\Property(property="ordering", type="integer", default=1),
 *     @OA\Property(property="position", type="string", enum={"header","footer"}, default="header"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", format="url", default="https://www.example.com/storage/media/1/conversions/image-100x100.jpg"),
 * )
 */
class SocialMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'link' => $this->link,
            'ordering' => $this->ordering,
            'position' => $this->position,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE),
        ];
    }
}
