<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\SliderPositionEnum;
use App\Enums\YesNoEnum;
use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SliderResource",
 *     title="SliderResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Title"),
 *     @OA\Property(property="description", type="string", default="Description"),
 *     @OA\Property(property="link", type="string", default="https://google.com"),
 *     @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *     @OA\Property(property="position", ref="#/components/schemas/SliderPositionEnum"),
 *     @OA\Property(property="has_timer", ref="#/components/schemas/YesNoEnum"),
 *     @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="ordering", type="integer", default="1"),
 *     @OA\Property(property="expired_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *          @OA\Property(property="image", type="string", default="https://example.com/image.jpg"),
 * )
 */
class SliderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'link'         => $this->link,
            'published'    => $this->published->toArray(),
            'position'     => $this->position->toArray(),
            'has_timer'    => $this->has_timer->toArray(),
            'published_at' => $this->published_at,
            'ordering'     => $this->ordering,
            'expired_at'   => $this->expired_at,
            'timer_start'  => $this->timer_start,
            'updated_at'   => $this->updated_at,
            'created_at'   => $this->created_at,
            'image'      => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_1280_400),

        ];
    }
}
