<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimpleUserResource",
 *     title="SimpleUserResource",
 *
 *     @OA\Property(property="id", type="integer", description="User id", example="1"),
 *     @OA\Property(property="name", type="string", example="John", nullable=true),
 *     @OA\Property(property="family", type="string", example="Doe", nullable=true),
 *     @OA\Property(property="mobile", type="string", example="09379151111"),
 *     @OA\Property(property="image", type="string", example="https://example.com/image.jpg"),
 * )
 */
class SimpleUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'family' => $this->family,
            'mobile' => $this->mobile,
            'image'  => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_512_SQUARE),
        ];
    }
}
