<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleTeammateResource",
 *     title="SimpleTeammateResource",
 *
 *     @OA\Property(property="id", type="integer", description="Teammate id", example="1"),
 *     @OA\Property(property="title", type="string", example="John Doe", nullable=true),
 *     @OA\Property(property="position", type="string", example="Senior Developer"),
 *     @OA\Property(property="image", type="string", example="https://example.com/image.jpg"),
 * )
 */
class SimpleTeammateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'position' => $this->position,
            'image'    => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE),
        ];
    }
}
