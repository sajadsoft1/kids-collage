<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleTagResource",
 *     title="SimpleTagResource",
 *
 *     @OA\Property(property="id", type="integer", description="Tag id", example="1"),
 *     @OA\Property(property="name", type="string", example="Laravel"),
 *     @OA\Property(property="slug", type="string", example="laravel"),
 *     @OA\Property(property="image", type="string", example="https://example.com/image.jpg"),
 * )
 */
class SimpleTagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'slug'  => $this->slug,
            'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_512_SQUARE),
        ];
    }
}
