<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TeammateResource",
 *     title="TeammateResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="John Doe"),
 *     @OA\Property(property="description", type="string", default="Senior Developer"),
 *     @OA\Property(property="position", type="string", default="Senior Developer"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="birthday", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class TeammateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'position'    => $this->position->toArray(),
            'published'   => $this->published->toArray(),
            'birthday'    => $this->birthday,
            'image'       => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
            'updated_at'  => $this->updated_at,
            'created_at'  => $this->created_at,
        ];
    }
}
