<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TagResource",
 *     title="TagResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="name", type="string", default="Laravel"),
 *     @OA\Property(property="slug", type="string", default="laravel"),
 *     @OA\Property(property="description", type="string", default="PHP framework"),
 *     @OA\Property(property="type", type="string", default="blog"),
 *     @OA\Property(property="order_column", type="integer", default="1"),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="is_in_use", type="boolean", default=true),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'type'         => $this->type,
            'order_column' => $this->order_column,
            'image'        => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
            'is_in_use'    => $this->isInUse(),
            'updated_at'   => $this->updated_at,
            'created_at'   => $this->created_at,
        ];
    }
}
