<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\BannerSizeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="BannerResource",
 *     title="BannerResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="description", type="string", default="Description"),
 *     @OA\Property(property="link", type="string", default="https://google.com"),
 *     @OA\Property(property="published",  ref="#/components/schemas/YesNoEnum"),
 *     @OA\Property(property="size",  ref="#/components/schemas/BannerSizeEnum"),
 *     @OA\Property(property="click",  type="integer", default="1"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 * )
 */
class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'size'         => $this->size->toArray(),
            'click'        => $this->click,
            'link'         => $this->link,
            'published_at' => $this->published_at,
            'updated_at'   => $this->updated_at,
            'created_at'   => $this->created_at,
            'image' => $this->resource->getFirstMediaUrl('image', $this->resource->getPrimaryResolution()),
        ];
    }
}
