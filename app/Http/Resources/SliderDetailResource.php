<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SliderDetailResource",
 *     title="SliderDetailResource",
 *     @OA\Property(property="id", type="integer", default="1"),
 *     @OA\Property(property="title", type="string", default="Slider Title"),
 *     @OA\Property(property="description", type="string", default="Slider Description"),
 *
 *     @OA\Property(property="updated_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 *     @OA\Property(property="created_at", type="string", default="2024-08-19T07:26:07.000000Z"),
 * )
 */
class SliderDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resource      = SliderResource::make($this)->toArray($request);
        $resource['id']=$this->id;

        return $resource;
    }
}
