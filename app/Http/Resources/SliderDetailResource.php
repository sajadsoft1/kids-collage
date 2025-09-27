<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * @OA\Schema(
 *     schema="SliderDetailResource",
 *     title="SliderDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *      @OA\Property(property="title", type="string", default="Title"),
 *      @OA\Property(property="description", type="string", default="Description"),
 *      @OA\Property(property="link", type="string", default="https://google.com"),
 *      @OA\Property(property="published", ref="#/components/schemas/BooleanEnum"),
 *      @OA\Property(property="position", ref="#/components/schemas/SliderPositionEnum"),
 *      @OA\Property(property="has_timer", ref="#/components/schemas/YesNoEnum"),
 *      @OA\Property(property="published_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *      @OA\Property(property="ordering", type="integer", default="1"),
 *      @OA\Property(property="expired_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *      @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *      @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *          @OA\Property(property="references", ref="#/components/schemas/ReferenceResource"),
 * )
 */
class SliderDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $resource = SliderResource::make($this)->toArray($request);

        return array_merge($resource, [
            'references'          => $this->whenLoaded('references', fn () => ReferenceResource::collection($this->references)),
        ]);
    }
}
