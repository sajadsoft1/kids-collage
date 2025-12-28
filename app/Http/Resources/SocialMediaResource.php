<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SocialMediaResource",
 *     title="SocialMediaResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="published",  ref="#/components/schemas/YesNoEnum"),
 *     @OA\Property(property="link", type="string", default="https://www.google.com"),
 *     @OA\Property(property="ordering", type="integer", default="1"),
 *     @OA\Property(property="position", type="string", default=" Position"),
 *     @OA\Property(property="color", type="string", default="#FFFFFF"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 * )
 */
class SocialMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'published' => $this->published,
            'link' => $this->link,
            'ordering' => $this->ordering,
            'position' => $this->position,
            'color' => $this->color,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
