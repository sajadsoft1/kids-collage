<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SimplePortfolioResource",
 *     title="SimplePortfolioResource",
 *
 *     @OA\Property(property="id", type="integer", description="Portfolio id", example="1"),
 *     @OA\Property(property="title", type="string", example="Website Redesign", nullable=true),
 *     @OA\Property(property="description", type="string", example="Modern responsive design", nullable=true),
 *     @OA\Property(property="image", type="string", example="https://example.com/image.jpg"),
 * )
 */
class SimplePortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'image'       => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE),
        ];
    }
}
